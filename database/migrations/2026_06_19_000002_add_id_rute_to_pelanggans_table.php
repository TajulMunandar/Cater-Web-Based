<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->foreignId('id_rute')->nullable()->after('id_gol')->constrained('rutes')->onDelete('set null');
            $table->dropForeign(['id_wilayah']);
            $table->dropColumn('id_wilayah');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->foreignId('id_wilayah')->nullable()->after('id_gol')->constrained('wilayahs')->onDelete('set null');
            $table->dropForeign(['id_rute']);
            $table->dropColumn('id_rute');
        });
    }
};
