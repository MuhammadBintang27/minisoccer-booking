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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'harga_weekday_member' => ['required', 'numeric', 'min:0'],
            'harga_weekend_member' => ['required', 'numeric', 'min:0'],
            'harga_weekday_nonmember' => ['required', 'numeric', 'min:0'],
            'harga_weekend_nonmember' => ['required', 'numeric', 'min:0'],
            'is_closed' => ['sometimes', 'boolean'],
        ];
    }
}
