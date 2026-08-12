<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class LanggananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isMember();
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
            'hari' => ['required', 'integer', 'between:1,7'],
            'bulan' => ['required', 'date_format:Y-m'],
            'layanan_tambahan_id' => ['sometimes', 'array'],
            'layanan_tambahan_id.*' => ['exists:layanan_tambahan,id'],
            'layanan_tambahan_jumlah' => ['sometimes', 'array'],
            'layanan_tambahan_jumlah.*' => ['integer', 'min:1', 'max:20'],
        ];
    }
}
