<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update check_in_method enum to only selfie and manual
        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_in_method ENUM('selfie', 'manual')");

        // Update check_out_method enum to only selfie and manual
        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_out_method ENUM('selfie', 'manual')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert check_in_method enum
        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_in_method ENUM('qr', 'gps', 'face_id', 'manual', 'selfie')");

        // Revert check_out_method enum
        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_out_method ENUM('qr', 'gps', 'face_id', 'manual', 'selfie')");
    }
};
