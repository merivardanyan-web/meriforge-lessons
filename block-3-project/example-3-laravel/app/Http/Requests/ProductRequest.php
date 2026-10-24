<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Առաջադրանք 2-ի Validator::product()-ը — PHP Դաս 21 */
class ProductRequest extends FormRequest
{
    /** Ո՞վ կարող է ուղարկել այս ֆորման — Gate՝ AppServiceProvider-ում */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-products') ?? false;
    }

    /** Checkbox-ը չի ուղարկվում, երբ նշված չէ — Դաս 6-ի տնային ⭐ */
    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:200'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'specs'       => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price'       => ['required', 'integer', 'min:1', 'max:99999999'],
            'discount'    => ['required', 'integer', 'between:0,90'],
            'stock'       => ['required', 'integer', 'min:0', 'max:100000'],
            'type'        => ['required', Rule::in(['physical', 'digital'])],
            'weight'      => ['required', 'integer', 'min:0', 'max:100000'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],   // Դաս 21-ի տնային
            'is_active'   => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'անվանումը',
            'category_id' => 'կատեգորիան',
            'specs'       => 'համառոտը',
            'description' => 'նկարագրությունը',
            'price'       => 'գինը',
            'discount'    => 'զեղչը',
            'stock'       => 'քանակը',
            'type'        => 'տեսակը',
            'weight'      => 'քաշը',
            'image'       => 'նկարը',
        ];
    }
}
