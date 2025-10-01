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
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop the specific foreign key constraint that's causing issues
        if (Schema::hasTable('package_features')) {
            try {
                // First try to drop the foreign key if it exists
                Schema::table('package_features', function (Blueprint $table) {
                    $table->dropForeign(['feature_id']);
                });
            } catch (\Exception $e) {
                // If that fails, try to find and drop by constraint name
                $constraint = \DB::selectOne("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE 
                        TABLE_SCHEMA = DATABASE() AND 
                        TABLE_NAME = 'package_features' AND 
                        CONSTRAINT_TYPE = 'FOREIGN KEY' AND
                        CONSTRAINT_NAME LIKE '%feature_id%'
                    LIMIT 1");
                
                if ($constraint) {
                    \DB::statement("ALTER TABLE package_features DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_features', function (Blueprint $table) {
            //
        });
    }
};
