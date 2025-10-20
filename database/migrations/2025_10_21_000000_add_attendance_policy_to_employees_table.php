<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('attendance_policy_id')
                  ->nullable()
                  ->constrained('attendance_policies')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['attendance_policy_id']);
            $table->dropColumn('attendance_policy_id');
        });
    }
};
