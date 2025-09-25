<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('instansi_subscription_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('subscription_addon_id')->constrained()->onDelete('cascade');
            $table->decimal('price_override', 15, 2)->nullable(); // Custom pricing
            $table->date('active_from');
            $table->date('active_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['instansi_id', 'subscription_addon_id'], 'instansi_addon_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('instansi_subscription_addons');
    }
};