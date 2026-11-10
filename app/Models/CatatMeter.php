<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatMeter extends Model
{
    /** @use HasFactory<\Database\Factories\CatatMeterFactory> */
    use HasFactory;

    public const VERIF_PENDING = 'pending';
    public const VERIF_APPROVED = 'approved';
    public const VERIF_DECLINED = 'declined';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'verifikasi_at' => 'datetime',
        'notif_dibaca_at' => 'datetime',
    ];

    /**
     * Data yang DITOLAK dianggap belum tercatat, sehingga otomatis tidak ikut
     * dihitung di rekap, dashboard, dan status "sudah dicatat" pelanggan.
     * Kolom sengaja tanpa awalan tabel agar tetap aman dipakai dengan alias
     * (mis. `from('catat_meters as cm')` di RekapController).
     *
     * Untuk melihat semua data (admin / daftar penolakan) pakai
     * CatatMeter::withoutGlobalScopes().
     */
    protected static function booted(): void
    {
        static::addGlobalScope('bukan_ditolak', function (Builder $query) {
            $query->where('status_verifikasi', '!=', self::VERIF_DECLINED);
        });
    }

    public function Pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function Petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function KondisiMeter()
    {
        return $this->belongsTo(KondisiMeter::class, 'id_kondisi');
    }

    public function FotoCater()
    {
        return $this->hasMany(FotoCater::class, 'id_cater', 'id');
    }
}
