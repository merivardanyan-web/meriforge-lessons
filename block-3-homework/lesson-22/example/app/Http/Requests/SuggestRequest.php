<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Դաս 06-ի validateSuggestion()-ը — PHP Դաս 21, տնային 2
 * Համեմատիր․ 60 տող if/elseif → 12 կանոն։ Սխալի տեքստերը՝ lang/hy/validation.php-ից
 */
class SuggestRequest extends FormRequest
{
    public const GENRES = ['վեպ', 'պատմավեպ', 'պատմվածք', 'հեքիաթ', 'պոեզիա', 'ֆանտաստիկա'];

    public function authorize(): bool
    {
        return true;                       // բոլորը կարող են առաջարկել
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'min:2', 'max:60'],
            'email'  => ['required', 'email', 'max:190'],
            'phone'  => ['nullable', 'regex:/^\+374\s?\d{2}\s?\d{6}$/'],
            'title'  => ['required', 'string', 'max:120'],
            'author' => ['nullable', 'string', 'max:80'],
            'genre'  => ['required', Rule::in(self::GENRES)],
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
            'agree'  => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'անունը', 'email' => 'էլ. հասցեն', 'phone' => 'հեռախոսը', 'title' => 'վերնագիրը',
            'author' => 'հեղինակը', 'genre' => 'ժանրը', 'reason' => 'պատճառը', 'agree' => 'կապ հաստատելու համաձայնությունը',
        ];
    }

    public function messages(): array
    {
        return ['phone.regex' => 'Հեռախոսը՝ +374 XX XXXXXX ձևաչափով'];
    }
}
