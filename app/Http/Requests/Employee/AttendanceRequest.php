<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'type' => 'required|in:check_in,check_out',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
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
            'type.required' => 'Tipe absensi harus dipilih.',
            'type.in' => 'Tipe absensi tidak valid.',
            'location.max' => 'Lokasi maksimal 255 karakter.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ];
    }
}
