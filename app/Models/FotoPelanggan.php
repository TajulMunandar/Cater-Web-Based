<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPelanggan extends Model
{
    /** @use HasFactory<\Database\Factories\FotoPelangganFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
