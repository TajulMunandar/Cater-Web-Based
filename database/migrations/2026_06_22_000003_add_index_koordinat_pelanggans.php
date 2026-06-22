<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            if (!Schema::hasIndex('pelanggans', 'idx_pelanggan_koordinat')) {
                $table->index(['lat', 'long', 'deleted_at'], 'idx_pelanggan_koordinat');
            }
            if (!Schema::hasIndex('pelanggans', 'idx_pelanggan_rute_koordinat')) {
                $table->index(['id_rute', 'deleted_at', 'lat', 'long'], 'idx_pelanggan_rute_koordinat');
            }
        });

        Schema::table('rutes', function (Blueprint $table) {
            if (!Schema::hasIndex('rutes', 'idx_rute_wilayah')) {
                $table->index(['id_wilayah'], 'idx_rute_wilayah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            if (Schema::hasIndex('pelanggans', 'idx_pelanggan_koordinat')) {
                $table->dropIndex('idx_pelanggan_koordinat');
            }
            if (Schema::hasIndex('pelanggans', 'idx_pelanggan_rute_koordinat')) {
                $table->dropIndex('idx_pelanggan_rute_koordinat');
            }
            if (Schema::hasIndex('pelanggans', 'idx_pelanggan_rute')) {
                $table->dropIndex('idx_pelanggan_rute');
            }
        });

        Schema::table('rutes', function (Blueprint $table) {
            if (Schema::hasIndex('rutes', 'idx_rute_wilayah')) {
                $table->dropIndex('idx_rute_wilayah');
            }
        });
    }
};
