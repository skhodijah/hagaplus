<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'expired'])->default('pending');
            $table->string('transaction_id', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_history');
    }
};
