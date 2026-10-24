<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Դաս 14-ի Validator::book()-ը — PHP Դաս 21 */
class BookRequest extends FormRequest
{
    /** Ո՞վ կարող է ուղարկել ֆորման — Gate՝ AppServiceProvider-ում */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-books') ?? false;
    }

    /** Checkbox-ը չի ուղարկվում, երբ նշված չէ (Դաս 06) ⭐ */
    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:200'],
            'author'     => ['required', 'string', 'max:100'],
            'genre_id'   => ['nullable', 'integer', Rule::exists('genres', 'id')],      // curl-ով genre_id=99999 → սխալ
            'year'       => ['nullable', 'integer', 'min:1', 'max:' . date('Y')],
            'pages'      => ['required', 'integer', 'min:1', 'max:5000'],
            'read_pages' => ['required', 'integer', 'min:0', 'lte:pages'],             // Դաս 04-ի «120%»-ը
            'rating'     => ['required', 'integer', 'between:0,5'],
            'color'      => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'link'       => ['nullable', 'url:http,https', 'max:255'],                 // javascript:-ը՝ ոչ
            'format'     => ['required', Rule::in(['paper', 'audio', 'ebook'])],
            'minutes'    => ['nullable', 'required_if:format,audio', 'integer', 'min:1', 'max:6000'],
            'narrator'   => ['nullable', 'string', 'max:100'],
            'lent_to'    => ['nullable', 'string', 'max:60'],
            'cover'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],   // տնային 5
            'is_active'  => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'      => 'վերնագիրը',
            'author'     => 'հեղինակը',
            'genre_id'   => 'ժանրը',
            'year'       => 'տարին',
            'pages'      => 'էջերը',
            'read_pages' => 'կարդացածը',
            'rating'     => 'գնահատականը',
            'color'      => 'գույնը',
            'link'       => 'հղումը',
            'format'     => 'տեսակը',
            'minutes'    => 'տևողությունը',
            'narrator'   => 'ընթերցողը',
            'lent_to'    => '«տվել եմ»',
            'cover'      => 'շապիկը',
        ];
    }

    public function messages(): array
    {
        return ['read_pages.lte' => 'Կարդացածը չի կարող լինել ավելի, քան էջերը'];
    }
}
