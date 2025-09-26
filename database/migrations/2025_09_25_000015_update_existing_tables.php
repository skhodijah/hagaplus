<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected function indexExists(string $tableName, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$database, $tableName, $indexName]
        );

        return !empty($result);
    }

    public function up()
    {
        // Add fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'effective_date')) {
                $table->date('effective_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('subscriptions', 'trial_ends_at')) {
                $table->date('trial_ends_at')->nullable()->after('effective_date');
            }
            if (!Schema::hasColumn('subscriptions', 'is_trial')) {
                $table->boolean('is_trial')->default(false)->after('trial_ends_at');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('price');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_date')) {
                $table->date('payment_date')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('subscriptions', 'notes')) {
                $table->text('notes')->nullable()->after('payment_date');
            }
        });

        // Add fields to instansis table
        Schema::table('instansis', function (Blueprint $table) {
            if (!Schema::hasColumn('instansis', 'retention_policy')) {
                $table->json('retention_policy')->nullable();
            }
            if (!Schema::hasColumn('instansis', 'archived_at')) {
                $table->timestamp('archived_at')->nullable();
            }
            if (!Schema::hasColumn('instansis', 'archived_by')) {
                $table->foreignId('archived_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });

        // Add soft deletes to important tables
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('users', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
            }
        });

        Schema::table('instansis', function (Blueprint $table) {
            if (!Schema::hasColumn('instansis', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('instansis', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('employees', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
            }
        });

        // Add indexes for performance
        Schema::table('attendances', function (Blueprint $table) {
            // Guard index creation if they already exist
            if (!$this->indexExists('attendances', 'attendances_user_id_status_attendance_date_index')) {
                $table->index(['user_id', 'status', 'attendance_date']);
            }
            if (!$this->indexExists('attendances', 'attendances_branch_id_attendance_date_index')) {
                $table->index(['branch_id', 'attendance_date']);
            }
            if (!$this->indexExists('attendances', 'attendances_status_attendance_date_index')) {
                $table->index(['status', 'attendance_date']);
            }
        });

        Schema::table('payrolls', function (Blueprint $table) {
            if (!$this->indexExists('payrolls', 'payrolls_payment_status_period_year_period_month_index')) {
                $table->index(['payment_status', 'period_year', 'period_month']);
            }
        });

        Schema::table('leaves', function (Blueprint $table) {
            if (!$this->indexExists('leaves', 'leaves_user_id_status_start_date_index')) {
                $table->index(['user_id', 'status', 'start_date']);
            }
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