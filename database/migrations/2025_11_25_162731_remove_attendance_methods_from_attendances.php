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
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'check_in_method')) {
                $table->dropColumn('check_in_method');
            }
            if (Schema::hasColumn('attendances', 'check_out_method')) {
                $table->dropColumn('check_out_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('check_in_method', ['selfie', 'manual'])->nullable()->after('check_in_time');
            $table->enum('check_out_method', ['selfie', 'manual'])->nullable()->after('check_out_time');
        });
    }
};
