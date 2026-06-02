<?php

namespace Tests\Feature;

use App\Models\Golongan;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelangganControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Wilayah $wilayah;
    private Golongan $golongan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
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
    }

    public function test_authentication_required_for_all_routes(): void
    {
        // Attempt accessing routes without authentication
        $this->get(route('pelanggan.index'))->assertRedirect(route('login'));
        $this->post(route('pelanggan.store'), [])->assertRedirect(route('login'));
        $this->get(route('pelanggan.show', 1))->assertRedirect(route('login'));
        $this->get(route('pelanggan.data'))->assertRedirect(route('login'));
    }

    public function test_index_returns_successful_response(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('pelanggan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.pages.pelanggan.pelanggan');
    }

    public function test_data_returns_datatables_json(): void
    {
        $this->actingAs($this->user);

        Pelanggan::factory()->create([
            'no_sambu' => 'SR-001',
            'no_kontrol' => 'KR-001',
            'nama' => 'DataTables Test',
        ]);

        $response = $this->get(route('pelanggan.data'), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => ['DT_RowIndex', 'no_sambu', 'no_kontrol', 'nama', 'alamat', 'wilayah', 'golongan', 'status_badge', 'status_text'],
            ],
        ]);
    }

    public function test_store_creates_pelanggan_successfully(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('pelanggan.store'), [
            'no_sambu' => 'SB-001',
            'no_kontrol' => 'KR-001',
            'nama' => 'Test User',
            'alamat' => 'Jl. Test No. 1',
            'telepon' => '08123456789',
            'type' => 'Rumah',
            'id_wilayah' => $this->wilayah->id,
            'id_gol' => $this->golongan->id,
            'status' => Pelanggan::STATUS_AKTIF,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $this->assertDatabaseHas('pelanggans', [
            'no_sambu' => 'SB-001',
            'nama' => 'Test User',
            'status' => Pelanggan::STATUS_AKTIF,
        ]);
    }

    public function test_store_fails_without_required_fields(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('pelanggan.store'), []);

        $response->assertSessionHasErrors(['no_sambu', 'no_kontrol', 'nama', 'alamat', 'status']);
    }

    public function test_store_fails_with_invalid_status(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('pelanggan.store'), [
            'no_sambu' => 'SB-002',
            'no_kontrol' => 'KR-002',
            'nama' => 'Invalid Status',
            'alamat' => 'Jl. Test',
            'status' => 'invalid_status_value',
        ]);

        $response->assertSessionHasErrors(['status']);
    }

    public function test_show_displays_pelanggan_data(): void
    {
        $this->actingAs($this->user);

        $pelanggan = Pelanggan::factory()->create([
            'no_sambu' => 'SB-003',
            'no_kontrol' => 'KR-003',
            'nama' => 'Show Test User',
            'alamat' => 'Jl. Tampil No. 1',
            'id_wilayah' => $this->wilayah->id,
            'id_gol' => $this->golongan->id,
            'status' => Pelanggan::STATUS_AKTIF,
        ]);

        $response = $this->get(route('pelanggan.show', $pelanggan->id));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.pages.pelanggan.show');
        $response->assertSee('Show Test User');
        $response->assertSee('SB-003');
    }

    public function test_show_returns_404_for_nonexistent_data(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('pelanggan.show', 99999));

        $response->assertStatus(404);
    }
}
