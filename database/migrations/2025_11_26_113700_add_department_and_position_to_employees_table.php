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
        Schema::table('employees', function (Blueprint $table) {
            // Drop old text columns if they exist
            if (Schema::hasColumn('employees', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }

            // Add new foreign keys
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('division_id')
                  ->constrained('departments')
                  ->onDelete('set null');

            $table->foreignId('position_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained('positions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['department_id', 'position_id']);

            // Restore old columns
            $table->string('position')->nullable();
            $table->string('department')->nullable();
        });
    }
};
