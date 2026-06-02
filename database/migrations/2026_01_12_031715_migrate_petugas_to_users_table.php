<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate petugas data to users table
        DB::insert("INSERT INTO users (name, email, password, created_at, updated_at) SELECT nama, email, password, created_at, updated_at FROM petugas");

        Schema::table('petugas', function (Blueprint $table) {
            // Add user_id foreign key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });

        // Update petugas to set user_id based on email match
        DB::statement("UPDATE petugas SET user_id = (SELECT id FROM users WHERE users.email = petugas.email)");

        Schema::table('petugas', function (Blueprint $table) {
            // Drop unique indexes first (required for SQLite compatibility)
            $table->dropUnique(['email']);
            $table->dropUnique(['username']);
            // Drop redundant columns
            $table->dropColumn(['email', 'password', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            // Add back the columns
            $table->string('email', 50)->nullable()->unique();
            $table->string('password', 255)->nullable();
            $table->string('username', 30)->nullable()->unique();
        });

        // Update petugas with data from users
        DB::statement("UPDATE petugas SET email = (SELECT email FROM users WHERE users.id = petugas.user_id), password = (SELECT password FROM users WHERE users.id = petugas.user_id), username = (SELECT name FROM users WHERE users.id = petugas.user_id)");

        Schema::table('petugas', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Delete migrated users
        DB::delete("DELETE FROM users WHERE id IN (SELECT user_id FROM petugas)");
    }
};
