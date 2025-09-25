<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('export_type', ['attendance', 'payroll', 'employees', 'full_backup', 'custom']);
            $table->enum('format', ['csv', 'excel', 'pdf', 'json']);
            $table->json('filters')->nullable(); // Date ranges, employee IDs, etc.
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->datetime('expires_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['instansi_id', 'status']);
            $table->index(['requested_by', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_exports');
    }
};