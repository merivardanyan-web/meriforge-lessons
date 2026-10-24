<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('book') ?? false;
    }

    public function rules(): array
    {
        return [
            // exists-ը միայն ասում է, որ ժամը կա։ Ազատ լինելը և ապագայում լինելը ստուգում է BookingService-ը
            'service_id' => ['required', 'integer', 'exists:services,id,deleted_at,NULL,is_active,1'],
            'slot_id'    => ['required', 'integer', 'exists:time_slots,id'],
            'note'       => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['service_id' => 'ծառայությունը', 'slot_id' => 'ժամը', 'note' => 'նշումը'];
    }

    public function messages(): array
    {
        return ['slot_id.required' => 'Ընտրիր ազատ ժամ։'];
    }
}
