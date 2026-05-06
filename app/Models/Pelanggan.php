<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

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
