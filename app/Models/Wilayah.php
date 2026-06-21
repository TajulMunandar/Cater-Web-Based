<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $wilayah
 * @property string $ket
 * @property string $center_lat
 * @property string $center_long
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Wilayah extends Model
{
    /** @use HasFactory<\Database\Factories\WilayahFactory> */
    use HasFactory;

    protected $fillable = [
        'wilayah',
        'ket',
        'center_lat',
        'center_long',
    ];

    public function rutes()
    {
        return $this->hasMany(Rute::class, 'id_wilayah');
    }
}
