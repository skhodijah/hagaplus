<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('period_year');
            $table->integer('period_month');
            $table->decimal('basic_salary', 15, 2);
            $table->json('allowances')->nullable(); // tunjangan
            $table->json('deductions')->nullable(); // potongan
            $table->decimal('overtime_amount', 15, 2)->default(0);
            $table->decimal('total_gross', 15, 2);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->date('payment_date')->nullable();
            $table->enum('payment_status', ['draft', 'processed', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->unique(['user_id', 'period_year', 'period_month']);
            $table->index(['period_year', 'period_month']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};

