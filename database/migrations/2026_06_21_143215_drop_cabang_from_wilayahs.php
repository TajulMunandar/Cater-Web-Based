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
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->dropColumn('cabang');
        });
    }

    public function down(): void
    {
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->string('cabang', 50)->nullable()->after('ket');
        });
    }
};
