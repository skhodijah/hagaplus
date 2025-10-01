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
        Schema::table('payment_history', function (Blueprint $table) {
            $table->renameColumn('company_id', 'instansi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_history', function (Blueprint $table) {
            $table->renameColumn('instansi_id', 'company_id');
        });
    }
};
