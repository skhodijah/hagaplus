<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instansi_id');
            $table->unsignedBigInteger('requested_by');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['open','in_progress','resolved','closed'])->default('open');
            $table->enum('priority', ['low','normal','high','urgent'])->default('normal');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['instansi_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
}; 