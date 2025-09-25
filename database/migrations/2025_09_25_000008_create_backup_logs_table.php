<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->onDelete('cascade');
            $table->enum('backup_type', ['full', 'incremental', 'instansi_specific', 'table_specific']);
            $table->enum('status', ['started', 'completed', 'failed'])->default('started');
            $table->string('backup_path')->nullable();
            $table->bigInteger('backup_size')->nullable(); // in bytes
            $table->json('tables_included')->nullable();
            $table->integer('records_count')->nullable();
            $table->datetime('started_at');
            $table->datetime('completed_at')->nullable();
            $table->text('error_details')->nullable();
            $table->string('initiated_by')->default('system'); // system, user, cron
            $table->timestamps();

            $table->index(['instansi_id', 'backup_type', 'status']);
            $table->index('started_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_logs');
    }
};