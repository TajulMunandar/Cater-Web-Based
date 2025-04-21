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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('photo', 100)->nullable();
            $table->string('nama', 50);
            $table->string('nip', 30)->unique();
            $table->string('no_hp1', 13);
            $table->string('no_hp2', 13)->nullable();
            $table->string('email', 50)->unique();
            $table->string('username', 30)->unique();
            $table->string('password', 255);
            $table->string('tipe_pekerjaan', 40);
            $table->tinyInteger('level');
            $table->string('jenis_pekerjaan', 35);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
