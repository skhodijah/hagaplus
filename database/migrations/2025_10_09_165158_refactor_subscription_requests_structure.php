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
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->integer('extension_months')->nullable()->after('amount');
            $table->foreignId('target_package_id')->nullable()->constrained('packages')->onDelete('set null')->after('extension_months');
            $table->dropColumn(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_requests', function (Blueprint $table) {
            $table->dropForeign(['target_package_id']);
            $table->dropColumn(['extension_months', 'target_package_id']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }
};
