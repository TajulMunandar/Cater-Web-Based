<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemutusan extends Model
{
    /** @use HasFactory<\Database\Factories\PemutusanFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function Kondisi()
    {
        return $this->belongsTo(KondisiMeter::class, 'id_kondisi');
    }

    public function FotoPemutusan()
    {
        return $this->hasMany(FotoPemutusan::class, 'id_pemutus', 'id');
    }
}
