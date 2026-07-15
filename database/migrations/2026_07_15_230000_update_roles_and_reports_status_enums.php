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
        // Update reports status ENUM to include 'ignored'
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'resolved', 'ignored') NOT NULL DEFAULT 'pending'");

        // Update users role ENUM to include 'banned'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'banned') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert reports status ENUM (clean up 'ignored' status first to avoid truncation errors)
        DB::table('reports')->where('status', 'ignored')->update(['status' => 'pending']);
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'resolved') NOT NULL DEFAULT 'pending'");

        // Revert users role ENUM (clean up 'banned' role first to avoid truncation errors)
        DB::table('users')->where('role', 'banned')->update(['role' => 'user']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }
};
