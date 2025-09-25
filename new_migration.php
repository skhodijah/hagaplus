<?php

// Migration 1: Package Change Requests (Point 1)
// File: database/migrations/2024_10_01_000001_create_package_change_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
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
            
            $table->index(['company_id', 'status']);
            $table->index('requested_effective_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_change_requests');
    }
};

// Migration 2: Subscription Transitions (Point 1)
// File: database/migrations/2024_10_01_000002_create_subscription_transitions_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('to_package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->enum('transition_type', ['upgrade', 'downgrade', 'renewal', 'new']);
            $table->datetime('effective_from');
            $table->datetime('effective_until')->nullable();
            $table->decimal('transition_amount', 15, 2);
            $table->decimal('prorate_credit', 15, 2)->default(0);
            $table->json('feature_changes')->nullable(); // Track what features changed
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['company_id', 'effective_from']);
            $table->index('transition_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_transitions');
    }
};

// Migration 3: Activity Logs (Point 3)
// File: database/migrations/2024_10_01_000003_create_activity_logs_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject'); // The model that was changed
            $table->nullableMorphs('causer'); // Who made the change (usually User)
            $table->json('properties')->nullable(); // Store old/new values
            $table->string('event')->nullable(); // created, updated, deleted, etc.
            $table->string('batch_uuid')->nullable(); // Group related activities
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
            $table->index(['company_id', 'created_at']);
            $table->index('log_name');
            $table->index('event');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};

// Migration 4: Features Table (Point 4)
// File: database/migrations/2024_10_01_000004_create_features_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['attendance', 'payroll', 'reporting', 'integration', 'customization']);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('config')->nullable(); // Store feature-specific configuration
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};

// Migration 5: Package Features (Point 4)
// File: database/migrations/2024_10_01_000005_create_package_features_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->json('limits')->nullable(); // e.g., max_employees, max_reports_per_month
            $table->json('config_override')->nullable(); // Override feature config for this package
            $table->timestamps();
            
            $table->unique(['package_id', 'feature_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_features');
    }
};

// Migration 6: Company Feature Overrides (Point 4)
// File: database/migrations/2024_10_01_000006_create_company_feature_overrides_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_feature_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled');
            $table->json('custom_limits')->nullable();
            $table->json('custom_config')->nullable();
            $table->text('reason')->nullable(); // Why was this override applied
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->foreignId('applied_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['company_id', 'feature_id', 'effective_from']);
            $table->index(['company_id', 'is_enabled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_feature_overrides');
    }
};

// Migration 7: Data Exports (Point 6)
// File: database/migrations/2024_10_01_000007_create_data_exports_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('export_type', ['attendance', 'payroll', 'employees', 'full_backup', 'custom']);
            $table->enum('format', ['csv', 'excel', 'pdf', 'json']);
            $table->json('filters')->nullable(); // Date ranges, employee IDs, etc.
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('file_path')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->datetime('expires_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index(['requested_by', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_exports');
    }
};

// Migration 8: Backup Logs (Point 6)
// File: database/migrations/2024_10_01_000008_create_backup_logs_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('backup_type', ['full', 'incremental', 'company_specific', 'table_specific']);
            $table->enum('status', ['started', 'completed', 'failed'])->default('started');
            $table->string('backup_path')->nullable();
            $table->bigInteger('backup_size')->nullable(); // in bytes
            $table->json('tables_included')->nullable();
            $table->integer('records_count')->nullable();
            $table->datetime('started_at');
            $table->datetime('completed_at')->nullable();
            $table->text('error_details')->nullable();
            $table->string('initiated_by')->default('system'); // system, user, cron
            $table->timestamps();
            
            $table->index(['company_id', 'backup_type', 'status']);
            $table->index('started_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backup_logs');
    }
};

// Migration 9: Compliance Logs (Point 7)
// File: database/migrations/2024_10_01_000009_create_compliance_logs_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('compliance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('compliance_type', ['data_retention', 'privacy_policy', 'gdpr', 'audit', 'security']);
            $table->string('event'); // data_deleted, policy_updated, access_granted, etc.
            $table->text('description');
            $table->json('affected_data')->nullable(); // What data was affected
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable(); // Additional compliance-specific data
            $table->timestamps();
            
            $table->index(['company_id', 'compliance_type']);
            $table->index(['event', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('compliance_logs');
    }
};

// Migration 10: Data Deletion Requests (Point 7)
// File: database/migrations/2024_10_01_000010_create_data_deletion_requests_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_deletion_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->enum('request_type', ['employee_data', 'attendance_data', 'payroll_data', 'full_company', 'specific_period']);
            $table->json('data_specification'); // Specific data to delete (user IDs, date ranges, etc.)
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->date('scheduled_deletion_date');
            $table->timestamp('completed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index('scheduled_deletion_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_deletion_requests');
    }
};

// Migration 11: Subscription Addons (Point 9)
// File: database/migrations/2024_10_01_000011_create_subscription_addons_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscription_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly']);
            $table->json('features')->nullable(); // Additional features this addon provides
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_addons');
    }
};

// Migration 12: Company Subscription Addons (Point 9)
// File: database/migrations/2024_10_01_000012_create_company_subscription_addons_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('company_subscription_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_addon_id')->constrained()->onDelete('cascade');
            $table->decimal('price_override', 15, 2)->nullable(); // Custom pricing
            $table->date('active_from');
            $table->date('active_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['company_id', 'subscription_addon_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_subscription_addons');
    }
};

// Migration 13: Discounts (Point 9)
// File: database/migrations/2024_10_01_000013_create_discounts_table.php

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
            $table->integer('usage_limit_per_company')->default(1);
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

// Migration 14: Discount Usage (Point 9)
// File: database/migrations/2024_10_01_000014_create_discount_usage_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('original_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->decimal('final_amount', 15, 2);
            $table->timestamps();
            
            $table->index(['discount_id', 'company_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_usage');
    }
};

// Migration 15: Update existing tables (Point 1, 6, 7, 9)
// File: database/migrations/2024_10_01_000015_update_existing_tables.php

return new class extends Migration
{
    public function up()
    {
        // Add fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('effective_date')->nullable()->after('status');
            $table->date('trial_ends_at')->nullable()->after('effective_date');
            $table->boolean('is_trial')->default(false)->after('trial_ends_at');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('price');
            $table->foreignId('discount_id')->nullable()->after('discount_amount')->constrained()->onDelete('set null');
        });

        // Add fields to companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->json('retention_policy')->nullable()->after('settings');
            $table->timestamp('archived_at')->nullable()->after('retention_policy');
            $table->foreignId('archived_by')->nullable()->after('archived_at')->constrained('users')->onDelete('set null');
        });

        // Add soft deletes to important tables
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->after('deleted_at')->constrained('users')->onDelete('set null');
        });

        // Add indexes for performance (Point 10)
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
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['effective_date', 'trial_ends_at', 'is_trial', 'discount_amount', 'discount_id']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropColumn(['retention_policy', 'archived_at', 'archived_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });

        Schema::table('companies', function (Blueprint $table) {
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

// Migration 16: Partitioning for large tables (Point 10)
// File: database/migrations/2024_10_01_000016_create_partitioned_tables.php

return new class extends Migration
{
    public function up()
    {
        // Create monthly partitioned attendance table for better performance
        DB::statement("
            CREATE TABLE attendances_archive (
                LIKE attendances INCLUDING ALL
            ) PARTITION BY RANGE (YEAR(attendance_date) * 100 + MONTH(attendance_date))
        ");

        // Create partitions for the next 2 years
        $currentYear = date('Y');
        for ($year = $currentYear; $year <= $currentYear + 1; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $partitionValue = $year * 100 + $month;
                $nextMonth = $month == 12 ? 1 : $month + 1;
                $nextYear = $month == 12 ? $year + 1 : $year;
                $nextPartitionValue = $nextYear * 100 + $nextMonth;
                
                DB::statement("
                    ALTER TABLE attendances_archive ADD PARTITION (
                        PARTITION p{$year}_{$month} VALUES LESS THAN ({$nextPartitionValue})
                    )
                ");
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('attendances_archive');
    }
};