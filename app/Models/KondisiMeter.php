<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $kondisi
 * @property string $keterangan
 * @property string $kode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class KondisiMeter extends Model
{
    /** @use HasFactory<\Database\Factories\KondisiMeterFactory> */
    use HasFactory;

    protected $fillable = [
        'kondisi',
        'keterangan',
        'kode',
    ];

    public function CatatMeter()
    {
        return $this->hasMany(CatatMeter::class, 'id_kondisi', 'id');
    }

    public function GantiMeter()
    {
        return $this->hasMany(GantiMeter::class, 'id_kondisi', 'id');
    }

    public function PelangganDetail()
    {
        return $this->hasMany(PelangganDetail::class, 'id_kondisi', 'id');
    }

    public function Pemutusan()
    {
        return $this->hasMany(Pemutusan::class, 'id_kondisi', 'id');
    }
}
