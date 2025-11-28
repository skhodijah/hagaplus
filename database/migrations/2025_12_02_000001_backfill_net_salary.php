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
        // Backfill net_salary with the existing gaji_bersih values for all payroll records
        DB::table('payrolls')->update([
            'net_salary' => DB::raw('gaji_bersih')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset net_salary to 0 (or null) when rolling back
        DB::table('payrolls')->update([
            'net_salary' => 0
        ]);
    }
};
