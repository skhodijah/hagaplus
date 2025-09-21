<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // siapa yang dapat notifikasi
            $table->string('title'); // judul notifikasi
            $table->text('message'); // isi pesan
            $table->string('type')->nullable(); // jenis notifikasi (info, warning, success, dll)
            $table->boolean('is_read')->default(false); // status sudah dibaca/belum
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
