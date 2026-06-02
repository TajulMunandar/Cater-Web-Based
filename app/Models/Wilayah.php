<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $wilayah
 * @property string $kode
 * @property string $ket
 * @property string $cabang
 * @property string $center_lat
 * @property string $center_long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Wilayah extends Model
{
    /** @use HasFactory<\Database\Factories\WilayahFactory> */
    use HasFactory;

    protected $fillable = [
        'wilayah',
        'kode',
        'ket',
        'cabang',
        'center_lat',
        'center_long',
    ];

    public function PelangganDetail()
    {
        return $this->hasMany(PelangganDetail::class, 'id_wilayah');
    }
}
