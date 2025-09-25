<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('original_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->decimal('final_amount', 15, 2);
            $table->timestamps();

            $table->index(['discount_id', 'instansi_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_usage');
    }
};