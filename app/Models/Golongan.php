<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property float $tarif_per_m3
 * @property float $biaya_admin
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Golongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'tarif_per_m3',
        'biaya_admin',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tarif_per_m3' => 'decimal:2',
            'biaya_admin' => 'decimal:2',
        ];
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'id_gol');
    }
}
