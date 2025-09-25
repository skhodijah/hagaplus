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
        // Update subscriptions table to include more status options
        Schema::table('subscriptions', function (Blueprint $table) {
            // Change enum to include suspended and canceled
            DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'inactive', 'expired', 'suspended', 'canceled') DEFAULT 'inactive' NOT NULL");
        });

        // Add settings for subscription extension threshold
        // Note: We'll handle this in the application code instead of migration
        // to avoid issues with nullable instansi_id constraints
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum back to original
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'inactive', 'expired') DEFAULT 'inactive'");

        // Remove the setting
        if (Schema::hasTable('settings')) {
            DB::table('settings')->where('key', 'subscription_extension_threshold_days')->delete();
        }
    }
};