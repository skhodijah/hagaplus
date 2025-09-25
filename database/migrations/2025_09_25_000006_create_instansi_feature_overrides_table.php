<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('instansi_feature_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled');
            $table->json('custom_limits')->nullable();
            $table->json('custom_config')->nullable();
            $table->text('reason')->nullable(); // Why was this override applied
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->foreignId('applied_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['instansi_id', 'feature_id', 'effective_from'], 'instansi_feature_override_unique');
            $table->index(['instansi_id', 'is_enabled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('instansi_feature_overrides');
    }
};