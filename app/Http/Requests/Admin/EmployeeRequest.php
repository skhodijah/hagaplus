<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'instansi_id' => 'required|exists:instansis,id',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'required|string|max:50',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive,terminated',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User harus dipilih.',
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'instansi_id.required' => 'Instansi harus dipilih.',
            'instansi_id.exists' => 'Instansi yang dipilih tidak valid.',
            'employee_id.required' => 'ID Karyawan harus diisi.',
            'position.required' => 'Posisi harus diisi.',
            'department.required' => 'Departemen harus diisi.',
            'salary.required' => 'Gaji harus diisi.',
            'salary.numeric' => 'Gaji harus berupa angka.',
            'hire_date.required' => 'Tanggal masuk harus diisi.',
            'status.required' => 'Status harus dipilih.',
        ];
    }
}
