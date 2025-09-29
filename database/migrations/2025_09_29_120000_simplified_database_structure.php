<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop redundant tables that can be simplified
        Schema::dropIfExists('features');
        Schema::dropIfExists('package_features');
        Schema::dropIfExists('instansi_feature_overrides');
        Schema::dropIfExists('subscription_addons');
        Schema::dropIfExists('instansi_subscription_addons');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discount_usage');
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('compliance_logs');
        Schema::dropIfExists('data_deletion_requests');
        Schema::dropIfExists('package_change_requests');
        Schema::dropIfExists('subscription_transitions');
        Schema::dropIfExists('data_exports');
        Schema::dropIfExists('company_themes');
        Schema::dropIfExists('theme_settings');

        // Simplify settings table - merge system_settings into settings
        if (Schema::hasTable('system_settings')) {
            // Migrate system settings to main settings table
            DB::statement("
                INSERT INTO settings (key, value, type, category, created_at, updated_at)
                SELECT
                    CONCAT('system.', `key`) as `key`,
                    `value`,
                    'string' as type,
                    'system' as category,
                    created_at,
                    updated_at
                FROM system_settings
                WHERE NOT EXISTS (
                    SELECT 1 FROM settings WHERE settings.key = CONCAT('system.', system_settings.key)
                )
            ");

            Schema::dropIfExists('system_settings');
        }

        // Simplify notifications - remove bulk notifications if not essential
        Schema::dropIfExists('bulk_notification_logs');

        // Ensure packages table has the right structure (features as JSON)
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'features')) {
                $table->json('features')->nullable()->after('max_branches');
            }
            if (!Schema::hasColumn('packages', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('features');
            }
        });

        // Ensure instansis table has subscription fields
        Schema::table('instansis', function (Blueprint $table) {
            if (!Schema::hasColumn('instansis', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null')->after('subdomain');
            }
            if (!Schema::hasColumn('instansis', 'subscription_start')) {
                $table->datetime('subscription_start')->nullable()->after('package_id');
            }
            if (!Schema::hasColumn('instansis', 'subscription_end')) {
                $table->datetime('subscription_end')->nullable()->after('subscription_start');
            }
            if (!Schema::hasColumn('instansis', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('subscription_end');
            }
            if (!Schema::hasColumn('instansis', 'max_employees')) {
                $table->integer('max_employees')->default(10)->after('is_active');
            }
            if (!Schema::hasColumn('instansis', 'max_branches')) {
                $table->integer('max_branches')->default(1)->after('max_employees');
            }
        });

        // Simplify subscriptions table - make it cleaner
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->enum('status', ['pending_verification', 'active', 'expired', 'cancelled'])->default('pending_verification')->after('package_id');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->default('transfer')->after('price');
            }
            if (!Schema::hasColumn('subscriptions', 'notes')) {
                $table->text('notes')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('subscriptions', 'cancelled_at')) {
                $table->datetime('cancelled_at')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('subscriptions', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('cancelled_at');
            }
        });

        // Add phone field to users for WhatsApp notifications
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
        });
    }

    public function down()
    {
        // Reverse the changes - restore dropped tables if needed
        // Note: This is a simplified rollback - in production you'd want full restoration

        // Remove added columns
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $columns = ['status', 'payment_method', 'notes', 'cancelled_at', 'cancel_reason'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('subscriptions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('instansis', function (Blueprint $table) {
            $columns = ['package_id', 'subscription_start', 'subscription_end', 'is_active', 'max_employees', 'max_branches'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('instansis', $column)) {
                    if ($column === 'package_id') {
                        $table->dropForeign(['package_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });

        // Note: Dropped tables cannot be easily restored in rollback
        // In production, you'd want migration files for each dropped table
    }
};