<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compliance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->enum('compliance_type', ['data_retention', 'privacy_policy', 'gdpr', 'audit', 'security']);
            $table->string('event'); // data_deleted, policy_updated, access_granted, etc.
            $table->text('description');
            $table->json('affected_data')->nullable(); // What data was affected
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable(); // Additional compliance-specific data
            $table->timestamps();

            $table->index(['instansi_id', 'compliance_type']);
            $table->index(['event', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_logs');
    }
};