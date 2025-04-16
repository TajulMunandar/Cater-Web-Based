<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoGantiMeter extends Model
{
    /** @use HasFactory<\Database\Factories\FotoGantiMeterFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function GantiMeter()
    {
        return $this->belongsTo(GantiMeter::class, 'id_ganti_meter');
    }
}
