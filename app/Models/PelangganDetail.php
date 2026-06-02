<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_pelanggan
 * @property int $id_petugas
 * @property int $rute
 * @property int $id_kondisi
 * @property \Illuminate\Support\Carbon $waktu_catat_meter
 * @property int $stand_terakhir
 * @property string|null $ket
 * @property int $urutan
 * @property int|null $id_wilayah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PelangganDetail extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'id_pelanggan',
        'id_petugas',
        'rute',
        'id_kondisi',
        'waktu_catat_meter',
        'stand_terakhir',
        'ket',
        'urutan',
        'id_wilayah',
    ];

    protected $casts = [
        'waktu_catat_meter' => 'datetime',
        'rute' => 'integer',
        'stand_terakhir' => 'integer',
        'urutan' => 'integer',
    ];

    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function Petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function Kondisi()
    {
        return $this->belongsTo(KondisiMeter::class, 'id_kondisi');
    }

    public function Wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }
}
