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
        Schema::create('ganti_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('pelanggans')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_petugas')->constrained('petugas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_kondisi')->constrained('kondisi_meters')->onDelete('restrict')->onUpdate('cascade');
            $table->dateTime('waktu_insert');
            $table->dateTime('waktu_eksekusi');
            $table->dateTime('waktu_upload');
            $table->string('gps', 25);
            $table->integer('stand_meter1');
            $table->integer('stand_meter2');
            $table->string('ket', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganti_meters');
    }
};
