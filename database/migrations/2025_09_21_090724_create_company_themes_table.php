<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('primary_color', 7)->default('#3b82f6');
            $table->string('secondary_color', 7)->default('#64748b');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('custom_css')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_themes');
    }
};

