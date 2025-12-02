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
        if (Schema::hasColumn('holidays', 'holiday_date')) {
            Schema::table('holidays', function (Blueprint $table) {
                $table->renameColumn('holiday_date', 'start_date');
            });
        }

        if (!Schema::hasColumn('holidays', 'end_date')) {
            Schema::table('holidays', function (Blueprint $table) {
                $table->date('end_date')->after('start_date')->nullable();
            });
            
            // Update existing records to have end_date = start_date
            \DB::statement('UPDATE holidays SET end_date = start_date');
            
            // Make end_date not null
            Schema::table('holidays', function (Blueprint $table) {
                $table->date('end_date')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('end_date');
            $table->renameColumn('start_date', 'holiday_date');
        });
    }
};
