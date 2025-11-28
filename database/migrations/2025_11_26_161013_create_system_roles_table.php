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
        Schema::create('system_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default system roles
        DB::table('system_roles')->insert([
            [
                'name' => 'Superadmin',
                'slug' => 'superadmin',
                'description' => 'Full system access across all instances',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Full access to admin dashboard and employee features',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Standard employee access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_roles');
    }
};
