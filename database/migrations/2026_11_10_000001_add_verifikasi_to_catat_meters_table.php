<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Verifikasi (approve / decline) data catat meter oleh admin.
     *
     * Data lama otomatis berstatus "approved" (default kolom), sedangkan data
     * baru dari aplikasi mobile dikirim sebagai "pending" oleh API.
     * Kolom `status` lama (aktif/tidak aktif) sengaja tidak disentuh.
     */
    public function up(): void
    {
        Schema::table('catat_meters', function (Blueprint $table) {
            $table->string('status_verifikasi', 20)->default('approved')->after('status');
            $table->text('alasan_tolak')->nullable()->after('status_verifikasi');
            $table->unsignedBigInteger('verifikasi_oleh')->nullable()->after('alasan_tolak');
            $table->timestamp('verifikasi_at')->nullable()->after('verifikasi_oleh');
            // Kapan petugas membuka notifikasi penolakan (null = belum dibaca)
            $table->timestamp('notif_dibaca_at')->nullable()->after('verifikasi_at');

            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('catat_meters', function (Blueprint $table) {
            $table->dropIndex(['status_verifikasi']);
            $table->dropColumn([
                'status_verifikasi',
                'alasan_tolak',
                'verifikasi_oleh',
                'verifikasi_at',
                'notif_dibaca_at',
            ]);
        });
    }
};
