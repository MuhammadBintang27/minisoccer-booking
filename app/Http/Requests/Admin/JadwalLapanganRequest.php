<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JadwalLapanganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Jam selesai boleh diisi "24:00" untuk mewakili tengah malam (slot terakhir yang berakhir
     * persis di pergantian hari, misal 23:00-24:00) — MySQL TIME menerima nilai itu dan
     * perbandingannya tetap benar (24:00 > 23:00), tapi format H:i PHP cuma menerima jam 00-23,
     * jadi dipakai regex sendiri di sini alih-alih date_format/after bawaan.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'jam_mulai' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
            'jam_selesai' => [
                'required',
                'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$|^24:00$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->filled('jam_mulai') && $value <= $this->input('jam_mulai')) {
                        $fail('Jam selesai harus setelah jam mulai (isi 24:00 untuk tengah malam).');
                    }
                },
            ],
            'harga_weekday_member' => ['required', 'numeric', 'min:0'],
            'harga_weekend_member' => ['required', 'numeric', 'min:0'],
            'harga_weekday_nonmember' => ['required', 'numeric', 'min:0'],
            'harga_weekend_nonmember' => ['required', 'numeric', 'min:0'],
            'is_closed' => ['sometimes', 'boolean'],
        ];
    }
}
