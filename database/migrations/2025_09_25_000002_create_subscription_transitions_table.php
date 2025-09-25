<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('from_package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('to_package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->enum('transition_type', ['upgrade', 'downgrade', 'renewal', 'new']);
            $table->datetime('effective_from');
            $table->datetime('effective_until')->nullable();
            $table->decimal('transition_amount', 15, 2);
            $table->decimal('prorate_credit', 15, 2)->default(0);
            $table->json('feature_changes')->nullable(); // Track what features changed
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['instansi_id', 'effective_from']);
            $table->index('transition_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_transitions');
    }
};