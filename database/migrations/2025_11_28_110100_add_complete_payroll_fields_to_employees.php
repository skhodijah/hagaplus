<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // I. DATA IDENTITAS DASAR (diisi karyawan)
            $table->string('tempat_lahir')->nullable()->after('date_of_birth');
            $table->text('alamat_ktp')->nullable()->after('address'); // rename address jadi alamat_domisili
            $table->enum('status_perkawinan', ['lajang', 'menikah', 'cerai'])->nullable()->after('gender');
            $table->integer('jumlah_tanggungan')->default(0)->after('status_perkawinan');
            $table->string('kewarganegaraan')->default('WNI')->after('jumlah_tanggungan');
            
            // Kontak darurat
            $table->string('emergency_contact_name')->nullable()->after('phone');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relation');
            
            // III. DATA PEKERJAAN (diisi HR)
            $table->enum('status_karyawan', ['tetap', 'kontrak', 'probation', 'magang'])->default('probation')->after('status');
            $table->string('grade_level')->nullable()->after('status_karyawan');
            $table->date('tanggal_berhenti')->nullable()->after('hire_date');
            
            // IV. DATA PAYROLL - Komponen Pendapatan Tetap (diisi HR)
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0)->after('salary');
            $table->decimal('tunjangan_transport', 15, 2)->default(0)->after('tunjangan_jabatan');
            $table->decimal('tunjangan_makan', 15, 2)->default(0)->after('tunjangan_transport');
            $table->decimal('tunjangan_hadir', 15, 2)->default(0)->after('tunjangan_makan');
            
            // V. DATA BPJS (diisi HR)
            $table->string('bpjs_kesehatan_number')->nullable()->after('npwp');
            $table->string('bpjs_kesehatan_faskes')->nullable()->after('bpjs_kesehatan_number');
            $table->date('bpjs_kesehatan_start_date')->nullable()->after('bpjs_kesehatan_faskes');
            $table->integer('bpjs_kesehatan_tanggungan')->default(0)->after('bpjs_kesehatan_start_date');
            $table->string('bpjs_kesehatan_card')->nullable()->after('bpjs_kesehatan_tanggungan'); // Path file kartu digital
            
            $table->string('bpjs_tk_number')->nullable()->after('bpjs_kesehatan_card');
            $table->boolean('bpjs_jp_aktif')->default(true)->after('bpjs_tk_number');
            $table->decimal('bpjs_jkk_rate', 5, 2)->default(0.24)->after('bpjs_jp_aktif'); // Default 0.24%
            $table->date('bpjs_tk_start_date')->nullable()->after('bpjs_jkk_rate');
            $table->string('bpjs_tk_card')->nullable()->after('bpjs_tk_start_date'); // Path file kartu digital
            
            // VI. DATA PERPAJAKAN (diisi HR)
            $table->enum('ptkp_status', ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'])->default('TK/0')->after('npwp');
            $table->enum('metode_pajak', ['gross', 'nett', 'gross_up'])->default('gross')->after('ptkp_status');
            
            // VIII. DATA OPSIONAL TAMBAHAN
            $table->string('foto_karyawan')->nullable()->after('bank_account_holder'); // Path foto
            $table->string('scan_ktp')->nullable()->after('foto_karyawan');
            $table->string('scan_npwp')->nullable()->after('scan_ktp');
            $table->string('scan_kk')->nullable()->after('scan_npwp');
            $table->text('catatan_hr')->nullable()->after('scan_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'alamat_ktp',
                'status_perkawinan',
                'jumlah_tanggungan',
                'kewarganegaraan',
                'emergency_contact_name',
                'emergency_contact_relation',
                'emergency_contact_phone',
                'status_karyawan',
                'grade_level',
                'tanggal_berhenti',
                'tunjangan_jabatan',
                'tunjangan_transport',
                'tunjangan_makan',
                'tunjangan_hadir',
                'bpjs_kesehatan_number',
                'bpjs_kesehatan_faskes',
                'bpjs_kesehatan_start_date',
                'bpjs_kesehatan_tanggungan',
                'bpjs_kesehatan_card',
                'bpjs_tk_number',
                'bpjs_jp_aktif',
                'bpjs_jkk_rate',
                'bpjs_tk_start_date',
                'bpjs_tk_card',
                'ptkp_status',
                'metode_pajak',
                'foto_karyawan',
                'scan_ktp',
                'scan_npwp',
                'scan_kk',
                'catatan_hr',
            ]);
        });
    }
};
