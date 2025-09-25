<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed_amount']);
            $table->decimal('value', 15, 2); // Percentage or fixed amount
            $table->decimal('max_discount', 15, 2)->nullable(); // Max discount for percentage type
            $table->integer('usage_limit')->nullable(); // How many times can be used
            $table->integer('usage_limit_per_instansi')->default(1);
            $table->integer('used_count')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->json('applicable_packages')->nullable(); // Which packages this applies to
            $table->enum('target', ['new_customers', 'existing_customers', 'all'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['valid_from', 'valid_until']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};