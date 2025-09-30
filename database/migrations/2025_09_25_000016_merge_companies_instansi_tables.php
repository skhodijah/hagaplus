<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add columns from companies table to instansis table (only if they don't exist)
        Schema::table('instansis', function (Blueprint $table) {
            if (!Schema::hasColumn('instansis', 'email')) {
                $table->string('email')->nullable()->after('nama_instansi');
            }
            if (!Schema::hasColumn('instansis', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('instansis', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('instansis', 'logo')) {
                $table->string('logo')->nullable()->after('address');
            }
            if (!Schema::hasColumn('instansis', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('set null')->after('logo');
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
            if (!Schema::hasColumn('instansis', 'settings')) {
                $table->json('settings')->nullable()->after('max_branches');
            }
        });

        // Migrate data from companies to instansis
        DB::statement("
            UPDATE instansis i
            INNER JOIN companies c ON i.id = c.id
            SET
                i.email = c.email,
                i.phone = c.phone,
                i.address = c.address,
                i.logo = c.logo,
                i.package_id = c.package_id,
                i.subscription_start = c.subscription_start,
                i.subscription_end = c.subscription_end,
                i.is_active = c.is_active,
                i.max_employees = c.max_employees,
                i.max_branches = c.max_branches,
                i.settings = c.settings
        ");

        // Update foreign key references from companies to instansis
        // Update attendance_policies
        DB::statement("
            UPDATE attendance_policies ap
            INNER JOIN companies c ON ap.company_id = c.id
            INNER JOIN instansis i ON c.id = i.id
            SET ap.company_id = i.id
        ");

        // Update branches
        DB::statement("
            UPDATE branches b
            INNER JOIN companies c ON b.company_id = c.id
            INNER JOIN instansis i ON c.id = i.id
            SET b.company_id = i.id
        ");

        // Update company_themes (only if table exists)
        if (Schema::hasTable('company_themes')) {
            DB::statement("
                UPDATE company_themes ct
                INNER JOIN companies c ON ct.company_id = c.id
                INNER JOIN instansis i ON c.id = i.id
                SET ct.company_id = i.id
            ");
        }

        // Update subscription_history
        DB::statement("
            UPDATE subscription_history sh
            INNER JOIN companies c ON sh.company_id = c.id
            INNER JOIN instansis i ON c.id = i.id
            SET sh.company_id = i.id
        ");

        // Drop foreign key constraints before dropping the table
        Schema::table('attendance_policies', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        if (Schema::hasTable('company_themes')) {
            Schema::table('company_themes', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
            });
        }

        Schema::table('subscription_history', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });

        // Add back foreign key constraints pointing to instansis table
        Schema::table('attendance_policies', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('instansis')->onDelete('cascade');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('instansis')->onDelete('cascade');
        });

        if (Schema::hasTable('company_themes')) {
            Schema::table('company_themes', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('instansis')->onDelete('cascade');
            });
        }

        Schema::table('subscription_history', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('instansis')->onDelete('cascade');
        });

        // Drop the companies table
        Schema::dropIfExists('companies');
    }

    public function down()
    {
        // Recreate companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
            $table->datetime('subscription_start')->nullable();
            $table->datetime('subscription_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('max_employees')->default(10);
            $table->integer('max_branches')->default(1);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // Migrate data back from instansis to companies
        DB::statement("
            INSERT INTO companies (
                id, name, email, phone, address, logo, package_id,
                subscription_start, subscription_end, is_active,
                max_employees, max_branches, settings, created_at, updated_at
            )
            SELECT
                id, nama_instansi, email, phone, address, logo, package_id,
                subscription_start, subscription_end, is_active,
                max_employees, max_branches, settings, created_at, updated_at
            FROM instansis
            WHERE email IS NOT NULL OR phone IS NOT NULL OR address IS NOT NULL
        ");

        // Remove added columns from instansis (only if they exist)
        Schema::table('instansis', function (Blueprint $table) {
            if (Schema::hasColumn('instansis', 'package_id')) {
                $table->dropForeign(['package_id']);
            }

            $columnsToDrop = [];
            if (Schema::hasColumn('instansis', 'email')) $columnsToDrop[] = 'email';
            if (Schema::hasColumn('instansis', 'phone')) $columnsToDrop[] = 'phone';
            if (Schema::hasColumn('instansis', 'address')) $columnsToDrop[] = 'address';
            if (Schema::hasColumn('instansis', 'logo')) $columnsToDrop[] = 'logo';
            if (Schema::hasColumn('instansis', 'package_id')) $columnsToDrop[] = 'package_id';
            if (Schema::hasColumn('instansis', 'subscription_start')) $columnsToDrop[] = 'subscription_start';
            if (Schema::hasColumn('instansis', 'subscription_end')) $columnsToDrop[] = 'subscription_end';
            if (Schema::hasColumn('instansis', 'is_active')) $columnsToDrop[] = 'is_active';
            if (Schema::hasColumn('instansis', 'max_employees')) $columnsToDrop[] = 'max_employees';
            if (Schema::hasColumn('instansis', 'max_branches')) $columnsToDrop[] = 'max_branches';
            if (Schema::hasColumn('instansis', 'settings')) $columnsToDrop[] = 'settings';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};