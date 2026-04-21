<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJumatBerkahRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ubah jadi true agar request diizinkan
        return true; 
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date|unique:jumat_berkah_schedules,tanggal',
            'target_porsi' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.unique' => 'Jadwal untuk tanggal ini sudah ada.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'target_porsi.min' => 'Target minimal adalah 1 porsi.',
        ];
    }
}
