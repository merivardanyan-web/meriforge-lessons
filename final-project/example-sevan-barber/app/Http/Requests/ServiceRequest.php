<?php

namespace App\Http\Requests;

use App\Models\TimeSlot;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-services') ?? false;
    }

    protected function prepareForValidation(): void
    {
        // checkbox-ը չնշված լինելու դեպքում չի ուղարկվում — PHP Դաս 06
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:80'],
            'description'  => ['nullable', 'string', 'max:500'],
            'duration_min' => ['required', 'integer', 'min:10', 'max:' . TimeSlot::LENGTH_MIN],
            'price'        => ['required', 'integer', 'min:500', 'max:100000'],
            'is_active'    => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'         => 'անվանումը',
            'description'  => 'նկարագրությունը',
            'duration_min' => 'տևողությունը',
            'price'        => 'գինը',
        ];
    }

    public function messages(): array
    {
        return ['duration_min.max' => 'Տևողությունը չի կարող գերազանցել ' . TimeSlot::LENGTH_MIN . ' րոպեն․ մեկ ամրագրումը մեկ ժամ է։'];
    }
}
