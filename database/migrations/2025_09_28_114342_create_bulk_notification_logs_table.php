<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bulk_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->string('target_type'); // all_admins, specific_admins, all_employees, specific_employees
            $table->json('target_ids')->nullable(); // IDs of targeted users
            $table->integer('total_sent');
            $table->unsignedBigInteger('sent_by');
            $table->timestamps();

            $table->foreign('sent_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_notification_logs');
    }
};
