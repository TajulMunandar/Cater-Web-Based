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
        Schema::create('foto_ganti_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ganti_meter')->constrained('ganti_meters')->onDelete('restrict')->onUpdate('cascade');
            $table->string('foto1', 100);
            $table->string('foto2', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_ganti_meters');
    }
};
