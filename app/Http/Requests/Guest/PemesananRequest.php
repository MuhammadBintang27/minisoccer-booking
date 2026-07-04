<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class PemesananRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'lapangan_id' => ['required', 'exists:lapangan,id'],
            'jadwal_id' => ['required', 'array', 'min:1'],
            'jadwal_id.*' => ['exists:jadwal_lapangan,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'nama_tamu' => ['required', 'string', 'max:255'],
            'no_hp_tamu' => ['required', 'string', 'max:20'],
            'layanan_tambahan_id' => ['sometimes', 'array'],
            'layanan_tambahan_id.*' => ['exists:layanan_tambahan,id'],
        ];
    }
}
