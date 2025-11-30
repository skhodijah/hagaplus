<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Tanggal draft dibuat (otomatis saat create)
            $table->date('created_date')->nullable()->after('period_month');
            
            // Rename payment_date untuk lebih jelas
            // payment_date akan diisi saat status diubah ke 'paid'
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('created_date');
        });
    }
};
