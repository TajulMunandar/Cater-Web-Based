<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GantiMeter extends Model
{
    /** @use HasFactory<\Database\Factories\GantiMeterFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];
    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function Petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function KondisiMeter()
    {
        return $this->belongsTo(KondisiMeter::class, 'id_kondisi');
    }

    public function FotoGantiMeter()
    {
        return $this->hasMany(FotoGantiMeter::class, 'id_ganti_meter', 'id');
    }
}
