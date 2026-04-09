<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    /** @use HasFactory<\Database\Factories\PetugasFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function GantiMeter()
    {
        return $this->hasMany(GantiMeter::class, 'id_petugas', 'id');
    }
    public function CatatMeter()
    {
        return $this->hasMany(CatatMeter::class, 'id_petugas', 'id');
    }
    public function Pemutusan()
    {
        return $this->hasMany(Pemutusan::class, 'id_petugas', 'id');
    }
    public function PelangganDetail()
    {
        return $this->hasMany(PelangganDetail::class, 'id_petugas', 'id');
    }
    public function Info()
    {
        return $this->hasMany(info::class, 'id_petugas', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
