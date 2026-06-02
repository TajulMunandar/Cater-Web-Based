<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $photo
 * @property string $nama
 * @property string $nip
 * @property string $no_hp1
 * @property string|null $no_hp2
 * @property string $tipe_pekerjaan
 * @property int $level
 * @property string $jenis_pekerjaan
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Petugas extends Model
{
    /** @use HasFactory<\Database\Factories\PetugasFactory> */
    use HasFactory;

    protected $fillable = [
        'photo',
        'nama',
        'nip',
        'no_hp1',
        'no_hp2',
        'tipe_pekerjaan',
        'level',
        'jenis_pekerjaan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

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
