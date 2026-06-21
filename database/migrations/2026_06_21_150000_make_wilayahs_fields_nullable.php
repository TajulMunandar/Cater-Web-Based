<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->string('ket', 100)->nullable()->change();
            $table->string('center_lat', 100)->nullable()->change();
            $table->string('center_long', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->string('ket', 100)->nullable(false)->change();
            $table->string('center_lat', 100)->nullable(false)->change();
            $table->string('center_long', 100)->nullable(false)->change();
        });
    }
};