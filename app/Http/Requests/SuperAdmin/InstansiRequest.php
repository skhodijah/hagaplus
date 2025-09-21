<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class InstansiRequest extends FormRequest
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
            'nama_instansi' => 'required|string|max:255',
            'subdomain' => 'required|string|max:100|unique:instansis,subdomain,' . $this->route('instansi'),
            'status_langganan' => 'required|in:active,inactive,suspended',
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
            'nama_instansi.required' => 'Nama instansi harus diisi.',
            'subdomain.required' => 'Subdomain harus diisi.',
            'subdomain.unique' => 'Subdomain sudah digunakan.',
            'status_langganan.required' => 'Status langganan harus diisi.',
        ];
    }
}
