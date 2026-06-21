<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_wilayah',
        'rute',
        'kode',
        'ket',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    public function Pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'id_rute');
    }
}
