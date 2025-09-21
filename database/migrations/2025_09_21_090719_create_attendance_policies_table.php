<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->json('work_days'); // [1,2,3,4,5] senin-jumat
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('break_duration')->default(60); // menit
            $table->integer('late_tolerance')->default(15); // menit
            $table->integer('early_checkout_tolerance')->default(15);
            $table->integer('overtime_after_minutes')->default(0);
            $table->json('attendance_methods'); // ['qr', 'gps', 'face_id']
            $table->boolean('auto_checkout')->default(false);
            $table->time('auto_checkout_time')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_policies');
    }
};
