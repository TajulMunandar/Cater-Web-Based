<?php

namespace App\Console\Commands;

use App\Services\BillingSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMasterData extends Command
{
    protected $signature = 'sync:master-data {--force : Sinkron walau BILLING_SYNC_ENABLED=false}';

    protected $description = 'Sinkronisasi data master (wilayah, rute, golongan, kondisi, petugas, pelanggan) dari billing';

    public function handle(BillingSyncService $service): int
    {
        if (!config('billing.sync_enabled') && !$this->option('force')) {
            $this->warn('BILLING_SYNC_ENABLED=false. Gunakan --force untuk memaksa.');

            return self::SUCCESS;
        }

        if (!config('billing.api_key')) {
            $this->error('BILLING_API_KEY belum diisi di .env');

            return self::FAILURE;
        }

        if (!$service->verify()) {
            $this->error('Verifikasi API key billing gagal. Periksa base_url dan api_key.');

            return self::FAILURE;
        }

        $this->info('Memulai sinkronisasi data master...');

        $start = microtime(true);
        $result = $service->syncMasterData();
        $elapsed = round(microtime(true) - $start, 2);

        foreach ($result as $table => $info) {
            $this->line(sprintf(
                '%-14s -> status: %s | synced: %d',
                $table,
                $info['code'] ?? $info['status'] ?? '-',
                $info['synced'] ?? 0
            ));
        }

        $this->info(sprintf('Sinkronisasi selesai dalam %s detik.', $elapsed));

        Log::info('sync:master-data selesai', [
            'duration' => $elapsed,
            'result' => $result,
        ]);

        return self::SUCCESS;
    }
}