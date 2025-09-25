<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->json('limits')->nullable(); // e.g., max_employees, max_reports_per_month
            $table->json('config_override')->nullable(); // Override feature config for this package
            $table->timestamps();

            $table->unique(['package_id', 'feature_id'], 'package_feature_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_features');
    }
};