<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiMeter extends Model
{
    /** @use HasFactory<\Database\Factories\KondisiMeterFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];
}
