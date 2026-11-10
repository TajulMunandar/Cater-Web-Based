<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->string('desa', 100)->nullable()->after('alamat');
            $table->string('kecamatan', 100)->nullable()->after('desa');
            $table->string('kabupaten', 100)->nullable()->after('kecamatan');
            $table->integer('stand_terakhir')->nullable()->after('long');
            $table->date('tgl_baca_terakhir')->nullable()->after('stand_terakhir');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropColumn(['desa', 'kecamatan', 'kabupaten', 'stand_terakhir', 'tgl_baca_terakhir']);
        });
    }
};