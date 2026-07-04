<?php

namespace App\Http\Requests\Member;

use App\Services\SubscriptionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

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
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->filled('hari') || ! $this->filled('bulan')) {
                return;
            }

            $bulan = Carbon::parse($this->input('bulan').'-01');
            $hari = (int) $this->input('hari');

            if (app(SubscriptionService::class)->hariSudahLewatUntukBulan($hari, $bulan)) {
                $validator->errors()->add('hari', 'Hari ini sudah lewat untuk bulan berjalan. Silakan pilih bulan depan.');
            }
        });
    }
}
