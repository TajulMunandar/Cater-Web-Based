<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan_details', function (Blueprint $table) {
            $table->dateTime('waktu_catat_meter')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan_details', function (Blueprint $table) {
            $table->dateTime('waktu_catat_meter')->nullable(false)->change();
        });
    }
};
