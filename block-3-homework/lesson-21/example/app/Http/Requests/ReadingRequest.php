<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** «Կարդացի այսօր» — Դաս 14-ի Validator::reading()-ը, PHP Դաս 21 */
class ReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-books') ?? false;
    }

    public function rules(): array
    {
        $book = $this->route('book');                     // route model binding-ի Book-ը

        return [
            'pages'   => ['required', 'integer', 'min:1', 'max:' . max(1, $book->pagesLeft())],
            'read_on' => ['required', 'date', 'before_or_equal:today'],
            'note'    => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['pages' => 'էջերը', 'read_on' => 'ամսաթիվը', 'note' => 'նշումը'];
    }

    public function messages(): array
    {
        // before_or_equal:today-ի :date-ը կլինի «today»՝ անգլերեն, այդ պատճառով՝ սեփական տեքստ
        return ['read_on.before_or_equal' => 'Ամսաթիվը չի կարող լինել ապագայում'];
    }
}
