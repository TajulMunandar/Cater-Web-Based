<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    /** @use HasFactory<\Database\Factories\InfoFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function Petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
