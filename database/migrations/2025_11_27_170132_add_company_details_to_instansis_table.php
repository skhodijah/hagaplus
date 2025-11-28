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
        Schema::table('instansis', function (Blueprint $table) {
            $table->string('npwp')->nullable()->after('address');
            $table->string('nib')->nullable()->after('npwp');
            $table->string('city')->nullable()->after('address');
            $table->string('website')->nullable()->after('email');
            // 'nama_instansi', 'email', 'phone', 'address', 'logo' already exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instansis', function (Blueprint $table) {
            $table->dropColumn(['npwp', 'nib', 'city', 'website']);
        });
    }
};
