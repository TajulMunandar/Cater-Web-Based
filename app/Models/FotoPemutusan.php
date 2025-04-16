<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPemutusan extends Model
{
    /** @use HasFactory<\Database\Factories\FotoPemutusanFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function Pemutusan()
    {
        return $this->belongsTo(Pemutusan::class, 'id_pemutus');
    }
}
