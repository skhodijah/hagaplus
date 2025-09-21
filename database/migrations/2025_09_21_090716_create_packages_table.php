<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('duration_days')->default(30);
            $table->integer('max_employees')->default(10);
            $table->integer('max_branches')->default(1);
            $table->json('features'); // ['qr', 'gps', 'face_id']
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
