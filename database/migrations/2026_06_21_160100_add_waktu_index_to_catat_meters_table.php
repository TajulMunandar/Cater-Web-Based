<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catat_meters', function (Blueprint $table) {
            $table->index('waktu');
        });
    }

    public function down(): void
    {
        Schema::table('catat_meters', function (Blueprint $table) {
            $table->dropIndex(['catat_meters_waktu_index']);
        });
    }
};
