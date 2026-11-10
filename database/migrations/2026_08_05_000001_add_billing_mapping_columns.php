<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->bigInteger('id_wilayah_billing')->nullable()->unique()->after('id');
        });

        Schema::table('rutes', function (Blueprint $table) {
            $table->bigInteger('id_rute_billing')->nullable()->unique()->after('id');
            $table->bigInteger('id_wilayah_billing')->nullable()->after('id_rute_billing');
        });

        Schema::table('golongans', function (Blueprint $table) {
            $table->bigInteger('id_golongan_billing')->nullable()->unique()->after('id');
        });

        Schema::table('kondisi_meters', function (Blueprint $table) {
            $table->bigInteger('id_kondisi_billing')->nullable()->unique()->after('id');
        });

        Schema::table('petugas', function (Blueprint $table) {
            $table->bigInteger('id_petugas_billing')->nullable()->unique()->after('id');
        });

        Schema::table('pelanggans', function (Blueprint $table) {
            $table->bigInteger('id_pelanggan_billing')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropUnique(['id_pelanggan_billing']);
            $table->dropColumn('id_pelanggan_billing');
        });

        Schema::table('petugas', function (Blueprint $table) {
            $table->dropUnique(['id_petugas_billing']);
            $table->dropColumn('id_petugas_billing');
        });

        Schema::table('kondisi_meters', function (Blueprint $table) {
            $table->dropUnique(['id_kondisi_billing']);
            $table->dropColumn('id_kondisi_billing');
        });

        Schema::table('golongans', function (Blueprint $table) {
            $table->dropUnique(['id_golongan_billing']);
            $table->dropColumn('id_golongan_billing');
        });

        Schema::table('rutes', function (Blueprint $table) {
            $table->dropColumn('id_wilayah_billing');
            $table->dropUnique(['id_rute_billing']);
            $table->dropColumn('id_rute_billing');
        });

        Schema::table('wilayahs', function (Blueprint $table) {
            $table->dropUnique(['id_wilayah_billing']);
            $table->dropColumn('id_wilayah_billing');
        });
    }
};