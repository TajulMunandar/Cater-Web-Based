<?php

namespace Tests\Feature;

use App\Models\Golongan;
use App\Models\KondisiMeter;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelangganBaruTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Wilayah $wilayah;
    private Golongan $golongan;
    private Petugas $petugas;
    private KondisiMeter $kondisi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->wilayah = Wilayah::create([
            'wilayah' => 'Wilayah Test',
            'kode' => 'TST',
            'ket' => 'Keterangan Test',
            'cabang' => 'Cabang Test',
            'center_lat' => '0.0000',
            'center_long' => '0.0000',
        ]);

        $this->golongan = Golongan::create([
            'kode' => 'TST',
            'nama' => 'Golongan Test',
            'tarif_per_m3' => 1000,
            'biaya_admin' => 5000,
        ]);

        $this->petugas = Petugas::create([
            'nama' => 'Petugas Test',
            'nip' => '1234567890',
            'no_hp1' => '081234567890',
            'tipe_pekerjaan' => 'Pencatat',
            'level' => 1,
            'jenis_pekerjaan' => 'Pencatat Meter',
            'user_id' => $this->user->id,
        ]);

        $this->kondisi = KondisiMeter::create([
            'kondisi' => 'Baik',
            'keterangan' => 'Kondisi Baik',
            'kode' => 'B',
        ]);
    }

    public function test_store_creates_pelanggan_successfully(): void
    {
        $response = $this->post(route('pelanggan.baru.store'), [
            'no_sambu' => 'SR-001',
            'no_kontrol' => 'KR-001',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test No. 123',
            'telepon' => '08123456789',
            'type' => 'Rumah Tinggal',
            'id_wilayah' => $this->wilayah->id,
            'id_gol' => $this->golongan->id,
            'status' => Pelanggan::STATUS_AKTIF,
            'lat' => -6.2088,
            'long' => 106.8456,
            'id_petugas' => $this->petugas->id,
            'rute' => 1,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => '2026-05-26T10:00',
            'stand_terakhir' => 100,
            'ket' => 'Keterangan test',
            'urutan' => 1,
            'id_wilayah_detail' => $this->wilayah->id,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('pelanggan.baru.index'));

        $this->assertDatabaseHas('pelanggans', [
            'no_sambu' => 'SR-001',
            'no_kontrol' => 'KR-001',
            'nama' => 'John Doe',
        ]);

        $pelanggan = Pelanggan::where('no_sambu', 'SR-001')->first();
        $this->assertDatabaseHas('pelanggan_details', [
            'id_pelanggan' => $pelanggan->id,
            'stand_terakhir' => 100,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(route('pelanggan.baru.store'), []);

        $response->assertSessionHasErrors([
            'no_sambu', 'no_kontrol', 'nama', 'alamat',
            'status', 'id_petugas', 'rute', 'id_kondisi',
            'waktu_catat_meter', 'stand_terakhir', 'urutan',
            'id_wilayah_detail',
        ]);
    }

    public function test_store_rejects_duplicate_no_sambu(): void
    {
        Pelanggan::create([
            'no_sambu' => 'SR-001',
            'no_kontrol' => 'KR-001',
            'nama' => 'Existing',
            'alamat' => 'Jl. Test',
            'status' => Pelanggan::STATUS_AKTIF,
        ]);

        $response = $this->post(route('pelanggan.baru.store'), [
            'no_sambu' => 'SR-001',
            'no_kontrol' => 'KR-002',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Test No. 123',
            'status' => Pelanggan::STATUS_AKTIF,
            'id_petugas' => $this->petugas->id,
            'rute' => 1,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => '2026-05-26T10:00',
            'stand_terakhir' => 100,
            'urutan' => 1,
            'id_wilayah_detail' => $this->wilayah->id,
        ]);

        $response->assertSessionHasErrors(['no_sambu']);
    }

    public function test_store_with_photo_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('foto.jpg', 100, 'image/jpeg');

        $response = $this->post(route('pelanggan.baru.store'), [
            'no_sambu' => 'SR-002',
            'no_kontrol' => 'KR-002',
            'nama' => 'Photo Test',
            'alamat' => 'Jl. Foto No. 1',
            'status' => Pelanggan::STATUS_AKTIF,
            'id_petugas' => $this->petugas->id,
            'rute' => 2,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => '2026-05-26T10:00',
            'stand_terakhir' => 200,
            'urutan' => 2,
            'id_wilayah_detail' => $this->wilayah->id,
            'foto_pelanggan' => [$file],
        ]);

        $response->assertSessionHas('success');

        $pelanggan = Pelanggan::where('no_sambu', 'SR-002')->first();
        $this->assertGreaterThan(0, $pelanggan->FotoPelanggan()->count());

        Storage::disk('public')->assertExists($pelanggan->FotoPelanggan()->first()->foto);
    }

    public function test_store_rejects_invalid_photo_format(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 100);

        $response = $this->post(route('pelanggan.baru.store'), [
            'no_sambu' => 'SR-003',
            'no_kontrol' => 'KR-003',
            'nama' => 'Invalid Photo',
            'alamat' => 'Jl. Salah No. 1',
            'status' => Pelanggan::STATUS_AKTIF,
            'id_petugas' => $this->petugas->id,
            'rute' => 3,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => '2026-05-26T10:00',
            'stand_terakhir' => 300,
            'urutan' => 3,
            'id_wilayah_detail' => $this->wilayah->id,
            'foto_pelanggan' => [$file],
        ]);

        $response->assertSessionHasErrors(['foto_pelanggan.0']);
    }

    public function test_update_modifies_pelanggan_successfully(): void
    {
        $pelanggan = Pelanggan::create([
            'no_sambu' => 'SR-010',
            'no_kontrol' => 'KR-010',
            'nama' => 'Before Update',
            'alamat' => 'Jl. Lama',
            'status' => Pelanggan::STATUS_AKTIF,
        ]);

        $pelanggan->PelangganDetail()->create([
            'id_petugas' => $this->petugas->id,
            'rute' => 10,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => now(),
            'stand_terakhir' => 500,
            'urutan' => 10,
            'id_wilayah' => $this->wilayah->id,
        ]);

        $response = $this->put(route('pelanggan.baru.update', $pelanggan->id), [
            'no_sambu' => 'SR-010',
            'no_kontrol' => 'KR-010',
            'nama' => 'After Update',
            'alamat' => 'Jl. Baru No. 99',
            'telepon' => '08987654321',
            'id_wilayah' => $this->wilayah->id,
            'id_gol' => $this->golongan->id,
            'status' => Pelanggan::STATUS_NON_AKTIF,
            'lat' => -6.2000,
            'long' => 106.8000,
            'id_petugas' => $this->petugas->id,
            'rute' => 10,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => '2026-05-26T10:00',
            'stand_terakhir' => 600,
            'ket' => 'Diperbarui',
            'urutan' => 10,
            'id_wilayah_detail' => $this->wilayah->id,
        ]);

        $response->assertSessionHas('update');
        $response->assertRedirect(route('pelanggan.baru.index'));

        $this->assertDatabaseHas('pelanggans', [
            'id' => $pelanggan->id,
            'nama' => 'After Update',
            'status' => Pelanggan::STATUS_NON_AKTIF,
        ]);

        $this->assertDatabaseHas('pelanggan_details', [
            'id_pelanggan' => $pelanggan->id,
            'stand_terakhir' => 600,
            'ket' => 'Diperbarui',
        ]);
    }

    public function test_destroy_removes_pelanggan_and_photos(): void
    {
        Storage::fake('public');

        $pelanggan = Pelanggan::create([
            'no_sambu' => 'SR-020',
            'no_kontrol' => 'KR-020',
            'nama' => 'To Delete',
            'alamat' => 'Jl. Hapus',
            'status' => Pelanggan::STATUS_AKTIF,
        ]);

        $file = UploadedFile::fake()->create('hapus.jpg', 100, 'image/jpeg');
        $path = $file->store('pelanggan-foto', 'public');
        $pelanggan->FotoPelanggan()->create(['foto' => $path]);

        $pelanggan->PelangganDetail()->create([
            'id_petugas' => $this->petugas->id,
            'rute' => 20,
            'id_kondisi' => $this->kondisi->id,
            'waktu_catat_meter' => now(),
            'stand_terakhir' => 700,
            'urutan' => 20,
            'id_wilayah' => $this->wilayah->id,
        ]);

        $response = $this->delete(route('pelanggan.baru.destroy', $pelanggan->id));

        $response->assertSessionHas('delete');
        $response->assertRedirect(route('pelanggan.baru.index'));

        // Soft delete: record tetap ada tapi dengan deleted_at
        $this->assertDatabaseHas('pelanggans', ['id' => $pelanggan->id]);
        $this->assertNotNull($pelanggan->fresh()->deleted_at);
        // Detail & foto dihapus permanent
        $this->assertDatabaseMissing('pelanggan_details', ['id_pelanggan' => $pelanggan->id]);
        $this->assertDatabaseMissing('foto_pelanggans', ['id_pelanggan' => $pelanggan->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_destroy_fails_with_invalid_id(): void
    {
        $response = $this->delete(route('pelanggan.baru.destroy', 99999));

        $response->assertStatus(404);
    }
}
