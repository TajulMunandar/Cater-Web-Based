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
}
