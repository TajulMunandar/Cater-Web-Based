<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $id_wilayah
 * @property int|null $id_gol
 * @property string $no_sambu
 * @property string $no_kontrol
 * @property string $nama
 * @property string $alamat
 * @property string|null $telepon
 * @property string|null $type
 * @property string $status
 * @property float|null $lat
 * @property float|null $long
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Pelanggan extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganFactory> */
    use HasFactory, SoftDeletes;

    const STATUS_AKTIF = 'aktif';
    const STATUS_NON_AKTIF = 'non-aktif';
    const STATUS_LIST = [self::STATUS_AKTIF, self::STATUS_NON_AKTIF];

    protected $fillable = [
        'no_sambu',
        'no_kontrol',
        'nama',
        'alamat',
        'telepon',
        'type',
        'id_wilayah',
        'id_gol',
        'status',
        'lat',
        'long',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:8',
            'long' => 'decimal:8',
        ];
    }

    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function GantiMeter()
    {
        return $this->hasMany(GantiMeter::class, 'id_pelanggan', 'id');
    }

    public function CatatMeter()
    {
        return $this->hasMany(CatatMeter::class, 'id_pelanggan', 'id');
    }

    public function Pemutusan()
    {
        return $this->hasMany(Pemutusan::class, 'id_pelanggan', 'id');
    }

    public function PelangganDetail()
    {
        return $this->hasMany(PelangganDetail::class, 'id_pelanggan', 'id');
    }

    public function PenggunaanAir()
    {
        return $this->hasMany(PenggunaanAir::class, 'id_pelanggan', 'id');
    }

    public function FotoPelanggan()
    {
        return $this->hasMany(FotoPelanggan::class, 'id_pelanggan', 'id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_gol');
    }
}
