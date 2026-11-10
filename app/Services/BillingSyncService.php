<?php

namespace App\Services;

use App\Models\Golongan;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Rute;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BillingSyncService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = (string) config('billing.base_url');
        $this->apiKey = (string) config('billing.api_key');
        $this->timeout = (int) config('billing.timeout', 30);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim($this->baseUrl, '/'))
            ->timeout($this->timeout)
            ->withToken($this->apiKey)
            ->acceptJson();
    }

    protected function ok(Response $response): bool
    {
        return $response->successful() || $response->status() === 207;
    }

    /** Verifikasi bahwa API key billing masih valid. */
    public function verify(): bool
    {
        return $this->client()->post('/auth/verify')->successful();
    }

    /**
     * Jalankan sinkronisasi semua data master dari billing.
     * Urutan penting: wilayah → rute → golongan → kondisi → petugas → pelanggan.
     */
    public function syncMasterData(): array
    {
        $result['wilayah'] = $this->syncWilayah();
        $result['rute'] = $this->syncRute();
        $result['golongan'] = $this->syncGolongan();
        $result['kondisi_meter'] = $this->syncKondisiMeter();
        $result['petugas'] = $this->syncPetugas();
        $result['pelanggan'] = $this->syncPelanggan();

        return $result;
    }

    public function syncWilayah(): array
    {
        return $this->syncCollection('/master/wilayah', function (array $item) {
            Wilayah::updateOrCreate(
                ['id_wilayah_billing' => $item['id'] ?? null],
                [
                    'wilayah' => $item['nama'] ?? '',
                ]
            );
        });
    }

    public function syncRute(): array
    {
        $wilayahByBillingId = Wilayah::pluck('id', 'id_wilayah_billing');

        return $this->syncCollection('/master/rute', function (array $item) use ($wilayahByBillingId) {
            $idWilayahBilling = $item['wilayah']['id'] ?? null;
            $idWilayahLokal = $idWilayahBilling !== null
                ? ($wilayahByBillingId[$idWilayahBilling] ?? null)
                : null;

            Rute::updateOrCreate(
                ['id_rute_billing' => $item['id'] ?? null],
                [
                    'id_wilayah_billing' => $idWilayahBilling,
                    'id_wilayah' => $idWilayahLokal,
                    'rute' => $item['rute'] ?? '',
                    'ket' => $item['keterangan'] ?? null,
                ]
            );
        });
    }

    public function syncGolongan(): array
    {
        return $this->syncCollection('/master/golongan', function (array $item) {
            Golongan::updateOrCreate(
                ['kode' => $item['kode'] ?? ''],
                [
                    'nama' => $item['ket'] ?? '',
                ]
            );
        });
    }

    public function syncKondisiMeter(): array
    {
        return $this->syncCollection('/master/kondisi-meter', function (array $item) {
            $kode = (string) ($item['kode'] ?? '');
            $ket = $item['ket'] ?? '';

            KondisiMeter::updateOrCreate(
                ['kode' => $kode],
                [
                    'kondisi' => $ket,
                    'keterangan' => $ket !== '' ? $ket : 'METER',
                ]
            );
        });
    }

    public function syncPetugas(): array
    {
        return $this->syncCollection('/master/petugas', function (array $item) {
            $idBilling = $item['id'] ?? null;
            if ($idBilling === null) {
                return;
            }

            $nama = $item['nama'] ?? '';

            $petugas = Petugas::where('id_petugas_billing', $idBilling)->first();

            if ($petugas) {
                $petugas->update(['nama' => $nama]);

                return;
            }

            // Billing hanya menyediakan id + nama. Karena schema lokal mewajibkan
            // relasi petugas ↔ user, kita buat akun user + petugas dengan nilai
            // unik turunan (password acak, bukan untuk login).
            $user = User::create([
                'name' => $nama,
                'username' => 'B-' . $idBilling,
                'email' => 'billing-' . $idBilling . '@tirta-pase.local',
                'password' => Hash::make(Str::random(40)),
                'level' => 1,
            ]);

            Petugas::create([
                'id_petugas_billing' => $idBilling,
                'user_id' => $user->id,
                'nama' => $nama,
                'nip' => 'B-' . $idBilling,
                'no_hp1' => '',
                'tipe_pekerjaan' => 'lapangan',
                'level' => 1,
                'jenis_pekerjaan' => 'pencatat_meter',
            ]);
        });
    }

    /** Sinkron pelanggan (looping paginasi). Billing tidak menyediakan relasi/assignment petugas. */
    public function syncPelanggan(int $perPage = 200): array
    {
        $ruteByCode = Rute::pluck('id', 'rute');
        $golonganByName = Golongan::pluck('id', 'nama');

        $page = 1;
        $synced = 0;

        do {
            $response = $this->client()->get('/pelanggan', [
                'page' => $page,
                'per_page' => $perPage,
            ]);

            if (!$this->ok($response)) {
                Log::error('BillingSync: sync pelanggan gagal', [
                    'page' => $page,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $this->result((int) $response->status(), $response->status(), 'SYNC_ERROR', $synced);
            }

            $items = $response->json('data') ?? [];
            foreach ((array) $items as $item) {
                $this->syncPelangganRow($item, $ruteByCode, $golonganByName);
                $synced++;
            }

            $meta = $response->json('meta') ?? [];
            $total = (int) ($meta['total'] ?? 0);
            $hasMore = is_array($items) ? count($items) > 0 : false;

            if ($total > 0 && $synced >= $total) {
                $hasMore = false;
            } elseif (!$hasMore) {
                break;
            }

            $page++;
        } while ($hasMore);

        return $this->result(200, 'OK', 'SYNC_SUCCESS', $synced);
    }

    protected function syncPelangganRow(array $item, $ruteByCode, $golonganByName): void
    {
        $idBilling = $item['no_pel'] ?? null;

        $data = [
            'nama' => $item['nama'] ?? '',
            'alamat' => $item['alamat'] ?? null,
            'lat' => $item['latitude'] ?? null,
            'long' => $item['longitude'] ?? null,
            'status' => Pelanggan::STATUS_AKTIF,
            'id_rute' => isset($item['rute']) ? ($ruteByCode[$item['rute']] ?? null) : null,
            'id_gol' => isset($item['golongan']) ? ($golonganByName[$item['golongan']] ?? null) : null,
        ];

        $pelanggan = $idBilling !== null
            ? Pelanggan::where('id_pelanggan_billing', $idBilling)->first()
            : null;

        if ($pelanggan) {
            // Jangan menimpa no_sambu / field identitas yang sudah ada.
            $pelanggan->update($data);

            return;
        }

        Pelanggan::create(array_merge(
            ['id_pelanggan_billing' => $idBilling],
            $data,
            // no_sambu memakai perfix "B" agar tidak bentrok dengan no_sambu lokal.
            ['no_sambu' => 'B' . (string) $idBilling]
        ));
    }

    protected function syncCollection(string $endpoint, callable $mapper): array
    {
        $response = $this->client()->get($endpoint);

        if (!$this->ok($response)) {
            Log::error("BillingSync: {$endpoint} gagal", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->result((int) $response->status(), $response->status(), 'SYNC_ERROR', 0);
        }

        $items = $response->json('data') ?? [];
        $synced = 0;
        foreach ((array) $items as $item) {
            if (is_array($item)) {
                $mapper($item);
                $synced++;
            }
        }

        return $this->result(200, 'OK', 'SYNC_SUCCESS', $synced);
    }

    protected function result(int $httpStatus, $status, string $code, int $synced): array
    {
        return [
            'http_status' => $httpStatus,
            'status' => $status,
            'code' => $code,
            'synced' => $synced,
        ];
    }
}