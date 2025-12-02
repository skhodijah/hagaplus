<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTaxForm;
use App\Models\Admin\Employee;
use App\Models\Payroll;
use App\Models\SuperAdmin\Instansi;
use Illuminate\Http\Request;

class TaxFormController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeTaxForm::with('employee');
        
        if ($request->has('year')) {
            $query->where('tax_year', $request->year);
        } else {
            $query->where('tax_year', date('Y'));
        }

        $forms = $query->paginate(10);
        return view('admin.tax_forms.index', compact('forms'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        return view('admin.tax_forms.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tax_year' => 'required|integer',
            'period_start' => 'required|integer',
            'period_end' => 'required|integer',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'data' => 'required|array',
        ]);

        $year = $request->tax_year;
        $periodStart = $request->period_start;
        $periodEnd = $request->period_end;
        $employeeId = $request->employee_ids[0]; // Single employee
        $data = $request->data;

        // Ensure critical hidden fields are set if they were empty in the form
        // (Though calculate() fills them, user might not have clicked it if they manually filled everything)
        // But for now we assume the form data is complete or at least what the user intends.
        
        // Re-generate form number if needed or trust the one from calculate()
        if (empty($data['h_01_nomor'])) {
             $data['h_01_nomor'] = '1.1-12.' . substr($year, -2) . '-' . str_pad($employeeId, 7, '0', STR_PAD_LEFT);
        }

        EmployeeTaxForm::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'tax_year' => $year,
            ],
            [
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'form_number' => $data['h_01_nomor'],
                'data' => $data,
                'created_by' => auth()->id(),
            ]
        );

        return redirect()->route('admin.tax-forms.index', ['year' => $year])->with('success', 'SPT berhasil disimpan.');
    }

    private function getPtkpAmount($status, $dependents)
    {
        $base = 54000000;
        $married = 4500000;
        $perDependent = 4500000;
        
        $total = $base;
        if ($status == 'menikah') {
            $total += $married;
        }
        $total += (min($dependents, 3) * $perDependent);
        
        return $total;
    }

    private function calculatePph21($pkp)
    {
        // Tarif 2024 (UU HPP)
        // 0 - 60jt : 5%
        // 60 - 250jt : 15%
        // 250 - 500jt : 25%
        // 500 - 5M : 30%
        // > 5M : 35%
        
        $tax = 0;
        
        if ($pkp > 5000000000) {
            $tax += ($pkp - 5000000000) * 0.35;
            $pkp = 5000000000;
        }
        if ($pkp > 500000000) {
            $tax += ($pkp - 500000000) * 0.30;
            $pkp = 500000000;
        }
        if ($pkp > 250000000) {
            $tax += ($pkp - 250000000) * 0.25;
            $pkp = 250000000;
        }
        if ($pkp > 60000000) {
            $tax += ($pkp - 60000000) * 0.15;
            $pkp = 60000000;
        }
        if ($pkp > 0) {
            $tax += $pkp * 0.05;
        }
        
        return $tax;
    }

    public function edit(EmployeeTaxForm $taxForm)
    {
        return view('admin.tax_forms.edit', compact('taxForm'));
    }

    public function update(Request $request, EmployeeTaxForm $taxForm)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        $taxForm->update([
            'data' => array_merge($taxForm->data, $request->data),
        ]);

        return redirect()->route('admin.tax-forms.index')->with('success', 'SPT berhasil diperbarui.');
    }

    public function publish(EmployeeTaxForm $taxForm)
    {
        $taxForm->update(['is_published' => !$taxForm->is_published]);
        return back()->with('success', 'Status publikasi diubah.');
    }

    public function destroy(EmployeeTaxForm $taxForm)
    {
        $taxForm->delete();
        return back()->with('success', 'SPT dihapus.');
    }

    public function bulkPublish(Request $request)
    {
        $year = $request->input('year', date('Y') - 1);
        EmployeeTaxForm::where('tax_year', $year)->update(['is_published' => true]);
        return back()->with('success', 'Semua SPT tahun ' . $year . ' berhasil dipublikasikan.');
    }
    
    public function calculate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tax_year' => 'required|integer',
            'period_start' => 'required|integer',
            'period_end' => 'required|integer',
        ]);

        $employee = Employee::with('user', 'position')->find($request->employee_id);
        $instansi = Instansi::first();

        // Fetch Payrolls
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->where('period_year', $request->tax_year)
            ->whereBetween('period_month', [$request->period_start, $request->period_end])
            ->get();

        // Calculate Components
        $gaji = $payrolls->sum(fn($p) => $p->gaji_pokok + $p->tunjangan_jabatan + $p->tunjangan_makan + $p->tunjangan_transport);
        $tunjanganLain = $payrolls->sum(fn($p) => $p->lembur + $p->tunjangan_hadir);
        $honorarium = 0;
        $premiAsuransi = 0;
        $natura = 0;
        $bonusThr = $payrolls->sum(fn($p) => $p->bonus + $p->thr);
        
        $bruto = $gaji + $tunjanganLain + $honorarium + $premiAsuransi + $natura + $bonusThr;

        // Pengurangan
        $monthsWorked = $payrolls->count();
        $biayaJabatan = min($bruto * 0.05, 500000 * $monthsWorked);
        $iuranPensiun = $payrolls->sum('bpjs_tk');
        $totalPengurangan = $biayaJabatan + $iuranPensiun;
        
        $netto = $bruto - $totalPengurangan;
        $nettoSetahun = $netto; 

        // PTKP
        $ptkpAmount = $this->getPtkpAmount($employee->status_perkawinan, $employee->jumlah_tanggungan);
        
        $pkp = floor(($nettoSetahun - $ptkpAmount) / 1000) * 1000;
        if ($pkp < 0) $pkp = 0;

        // PPh21
        $pph21Terutang = $this->calculatePph21($pkp);
        $pph21Dipotong = $payrolls->sum('pph21');

        $data = [
            'h_01_nomor' => '1.1-12.' . substr($request->tax_year, -2) . '-' . str_pad($employee->id, 7, '0', STR_PAD_LEFT),
            'h_03_npwp_pemotong' => $instansi ? $instansi->npwp : '00.000.000.0-000.000',
            'h_04_nama_pemotong' => $instansi ? $instansi->nama_instansi : 'PT. CONTOH',
            
            'a_01_npwp' => $employee->npwp,
            'a_02_nik' => $employee->nik,
            'a_03_nama' => $employee->user->name,
            'a_04_alamat' => $employee->address,
            'a_05_jenis_kelamin' => $employee->gender,
            'a_06_status_ptkp' => ($employee->status_perkawinan == 'menikah' ? 'K' : 'TK') . '/' . $employee->jumlah_tanggungan,
            'a_07_jabatan' => $employee->position ? $employee->position->name : '-',
            'a_09_negara' => 'ID',

            'b_01_gaji' => $gaji,
            'b_02_tunjangan_pph' => 0,
            'b_03_tunjangan_lain' => $tunjanganLain,
            'b_04_honorarium' => $honorarium,
            'b_05_premi_asuransi' => $premiAsuransi,
            'b_06_natura' => $natura,
            'b_07_bonus' => $bonusThr,
            'b_08_bruto' => $bruto,
            'b_09_biaya_jabatan' => $biayaJabatan,
            'b_10_iuran_pensiun' => $iuranPensiun,
            'b_11_total_pengurangan' => $totalPengurangan,
            'b_12_netto' => $netto,
            'b_13_netto_lalu' => 0,
            'b_14_netto_setahun' => $nettoSetahun,
            'b_15_ptkp' => $ptkpAmount,
            'b_16_pkp' => $pkp,
            'b_17_pph_terutang' => $pph21Terutang,
            'b_18_pph_lalu' => 0,
            'b_19_pph_terutang_total' => $pph21Terutang,
            'b_20_pph_dilunasi' => $pph21Dipotong,
            
            'c_03_tanggal' => date('d-m-Y'),
        ];

        return response()->json($data);
    }
    
    public function print(EmployeeTaxForm $taxForm)
    {
        return view('admin.tax_forms.print', compact('taxForm'));
    }
}
