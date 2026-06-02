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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_wilayah')->nullable()->constrained('wilayahs')->onDelete('set null');
            $table->foreignId('id_gol')->nullable()->constrained('golongans')->onDelete('set null');
            $table->string('no_sambu', 30)->unique();
            $table->string('no_kontrol', 50)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('telepon', 20)->nullable();
            $table->string('type', 50)->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
