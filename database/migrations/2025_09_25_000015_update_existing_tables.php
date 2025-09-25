<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('effective_date')->nullable()->after('status');
            $table->date('trial_ends_at')->nullable()->after('effective_date');
            $table->boolean('is_trial')->default(false)->after('trial_ends_at');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('price');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->date('payment_date')->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('payment_date');
        });

        // Add fields to instansis table
        Schema::table('instansis', function (Blueprint $table) {
            $table->json('retention_policy')->nullable()->after('catatan');
            $table->timestamp('archived_at')->nullable()->after('retention_policy');
            $table->foreignId('archived_by')->nullable()->after('archived_at')->constrained('users')->onDelete('set null');
        });

        // Add soft deletes to important tables
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        Schema::table('instansis', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        // Add indexes for performance
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'attendance_date']);
            $table->index(['branch_id', 'attendance_date']);
            $table->index(['status', 'attendance_date']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->index(['payment_status', 'period_year', 'period_month']);
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'start_date']);
        });
    }

    public function down()
    {
        // Remove added columns and indexes
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['effective_date', 'trial_ends_at', 'is_trial', 'payment_status', 'payment_method', 'payment_date', 'notes']);
        });

        Schema::table('instansis', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropColumn(['retention_policy', 'archived_at', 'archived_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });

        Schema::table('instansis', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
    }
};