<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** «Ավելացնել աշխատանքային ժամեր»․ օր + սկիզբ + ավարտ → մեկժամյա պատուհաններ */
class SlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('staff-area') ?? false;
    }

    public function rules(): array
    {
        return [
            'date'     => ['required', 'date_format:Y-m-d', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(30)->toDateString()],
            'from'     => ['required', 'integer', 'between:8,21'],
            'to'       => ['required', 'integer', 'between:9,22', 'gt:from'],
            // Ադմինը ընտրում է վարսավիրին, վարսավիրը՝ միշտ ինքն է (կոնտրոլեր)
            'staff_id' => [Rule::requiredIf($this->user()->isAdmin()), 'nullable', 'integer', Rule::exists('users', 'id')->where('role', 'staff')],
        ];
    }

    public function attributes(): array
    {
        return ['date' => 'օրը', 'from' => 'սկիզբը', 'to' => 'ավարտը', 'staff_id' => 'վարսավիրը'];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal'  => 'Անցած օրվա համար ժամեր չեն ավելացվում։',
            'date.before_or_equal' => 'Ժամերը բացվում են առավելագույնը 30 օր առաջ։',
            'to.gt'                => 'Ավարտը պիտի լինի սկզբից ուշ։',
        ];
    }
}
