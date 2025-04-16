<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelangganDetail extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganDetailFactory> */
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
    public function Kondisi()
    {
        return $this->belongsTo(KondisiMeter::class, 'id_kondisi');
    }
    public function Wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }
}
