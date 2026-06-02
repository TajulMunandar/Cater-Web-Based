<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petugas')->constrained('petugas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('rute');
            $table->foreignId('id_kondisi')->constrained('kondisi_meters')->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('waktu_catat_meter');
            $table->integer('stand_terakhir');
            $table->string('ket', 100);
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan_details');
    }
};
