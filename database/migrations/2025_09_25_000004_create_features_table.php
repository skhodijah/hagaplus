<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['attendance', 'payroll', 'reporting', 'integration', 'customization']);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('config')->nullable(); // Store feature-specific configuration
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};