<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Դաս 6-ի validateContact()-ը — PHP Դաս 21-ի տնային */
class ContactRequest extends FormRequest
{
    public const TOPICS = [
        'order'   => 'Պատվեր',
        'support' => 'Տեխնիկական աջակցություն',
        'other'   => 'Այլ',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'min:2', 'max:60'],
            'email'   => ['required', 'email', 'max:190'],
            'topic'   => ['required', Rule::in(array_keys(self::TOPICS))],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'անունը', 'email' => 'էլ. հասցեն', 'topic' => 'թեման', 'message' => 'հաղորդագրությունը'];
    }
}
