<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->foreignId('current_package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('requested_package_id')->constrained('packages')->onDelete('cascade');
            $table->enum('type', ['upgrade', 'downgrade']);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->date('requested_effective_date');
            $table->decimal('prorate_amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['instansi_id', 'status']);
            $table->index('requested_effective_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_change_requests');
    }
};