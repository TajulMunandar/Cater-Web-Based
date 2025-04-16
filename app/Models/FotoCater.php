<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoCater extends Model
{
    /** @use HasFactory<\Database\Factories\FotoCaterFactory> */
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function CatatMeter()
    {
        return $this->belongsTo(CatatMeter::class, 'id_cater');
    }
}
