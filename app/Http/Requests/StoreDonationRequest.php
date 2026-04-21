<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
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
    public function rules(): array {
    return [
        'nama_donatur' => 'required|string|max:255',
        'jenis_donasi' => 'required|in:Uang,Makanan',
        'jumlah_donasi' => 'nullable|numeric',
        'jumlah_porsi' => 'nullable|integer',
    ];
}
}
