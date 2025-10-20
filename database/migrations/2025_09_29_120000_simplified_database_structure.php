<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Drop foreign keys first before dropping tables
        if (Schema::hasTable('package_features')) {
            $constraint = DB::selectOne("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE
                    TABLE_SCHEMA = DATABASE() AND
                    TABLE_NAME = 'package_features' AND
                    CONSTRAINT_TYPE = 'FOREIGN KEY' AND
                    CONSTRAINT_NAME LIKE '%feature_id%'
                LIMIT 1");

            if ($constraint) {
                DB::statement("ALTER TABLE package_features DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
            }
        }

        if (Schema::hasTable('instansi_feature_overrides')) {
            $constraint = DB::selectOne("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE
                    TABLE_SCHEMA = DATABASE() AND
                    TABLE_NAME = 'instansi_feature_overrides' AND
                    CONSTRAINT_TYPE = 'FOREIGN KEY' AND
                    CONSTRAINT_NAME LIKE '%feature_id%'
                LIMIT 1");

            if ($constraint) {
                DB::statement("ALTER TABLE instansi_feature_overrides DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
            }
        }

        // Drop redundant tables that can be simplified
        // Drop child tables first to avoid foreign key constraints
        Schema::dropIfExists('package_features');
        Schema::dropIfExists('instansi_feature_overrides');
        Schema::dropIfExists('instansi_subscription_addons');
        Schema::dropIfExists('discount_usage');
        Schema::dropIfExists('features');
        Schema::dropIfExists('subscription_addons');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('compliance_logs');
        Schema::dropIfExists('data_deletion_requests');
        Schema::dropIfExists('package_change_requests');
        Schema::dropIfExists('subscription_transitions');
        Schema::dropIfExists('data_exports');
        Schema::dropIfExists('company_themes');
        Schema::dropIfExists('theme_settings');

        // Simplify settings table - merge system_settings into settings
        // Check if foreign key exists before dropping
        $foreignKeyExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'settings'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME LIKE '%instansi_id%'
        ");

        // Check if unique constraint exists
        $uniqueConstraintExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'settings'
            AND CONSTRAINT_TYPE = 'UNIQUE'
            AND CONSTRAINT_NAME LIKE '%instansi_id%key%'
        ");

        // Check if key unique constraint exists
        $keyUniqueConstraintExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'settings'
            AND CONSTRAINT_TYPE = 'UNIQUE'
            AND CONSTRAINT_NAME LIKE '%key%'
            AND CONSTRAINT_NAME NOT LIKE '%instansi_id%'
        ");

        Schema::table('settings', function (Blueprint $table) use ($foreignKeyExists, $uniqueConstraintExists, $keyUniqueConstraintExists) {
            // Drop foreign key constraint first (only if it exists)
            if (!empty($foreignKeyExists)) {
                $table->dropForeign(['instansi_id']);
            }

            // Make instansi_id nullable for global settings
            $table->foreignId('instansi_id')->nullable()->change();

            // Drop the unique constraint that includes instansi_id (only if it exists)
            if (!empty($uniqueConstraintExists)) {
                $table->dropUnique(['instansi_id', 'key']);
            }

            if (!Schema::hasColumn('settings', 'category')) {
                $table->string('category')->default('general')->after('type');
            }
            if (!Schema::hasColumn('settings', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (!Schema::hasColumn('settings', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('description');
            }

            // Remove duplicate keys, keeping the one with the lowest id (oldest)
            DB::statement("
                DELETE s1 FROM settings s1
                INNER JOIN settings s2
                WHERE s1.id > s2.id AND s1.key = s2.key
            ");

            // Add new unique constraint on instansi_id and key (for per-instansi settings)
            $table->unique(['instansi_id', 'key']);
        });

        if (Schema::hasTable('system_settings')) {
            // Migrate system settings to main settings table
            DB::statement("
                INSERT INTO settings (`key`, `value`, `type`, `category`, `created_at`, `updated_at`)
                SELECT
                    CONCAT('system.', `key`) as `key`,
                    `value`,
                    'string' as type,
                    'system' as category,
                    created_at,
                    updated_at
                FROM system_settings
                WHERE NOT EXISTS (
                    SELECT 1 FROM settings WHERE settings.`key` = CONCAT('system.', system_settings.`key`)
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
                $table->enum('status', ['pending_verification', 'active', 'inactive', 'expired', 'cancelled'])->default('pending_verification')->after('end_date');
            } else {
                // Modify existing status enum to include more values
                DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending_verification', 'active', 'inactive', 'expired', 'cancelled') DEFAULT 'pending_verification'");
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