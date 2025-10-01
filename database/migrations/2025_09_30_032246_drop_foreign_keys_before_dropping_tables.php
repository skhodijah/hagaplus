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
        // Drop foreign key constraints first
        Schema::table('package_features', function (Blueprint $table) {
            if (Schema::hasTable('package_features')) {
                $table->dropForeign(['feature_id']);
            }
        });

        // Also drop any other foreign keys that might reference the tables we're about to drop
        Schema::table('instansi_feature_overrides', function (Blueprint $table) {
            if (Schema::hasTable('instansi_feature_overrides')) {
                $table->dropForeign(['feature_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration, no need to recreate the foreign keys
        // as they will be recreated if the tables are recreated
    }
};
