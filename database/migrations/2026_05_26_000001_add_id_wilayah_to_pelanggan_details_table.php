<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan_details', function (Blueprint $table) {
            $table->foreignId('id_wilayah')
                ->nullable()
                ->after('urutan')
                ->constrained('wilayahs')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan_details', function (Blueprint $table) {
            $table->dropForeign(['id_wilayah']);
            $table->dropColumn('id_wilayah');
        });
    }
};
