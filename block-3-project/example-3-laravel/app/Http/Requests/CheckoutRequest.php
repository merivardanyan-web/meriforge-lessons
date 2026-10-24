<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public const DELIVERY = [
        'courier' => ['Առաքում Երևանում', 'Վաղը · անվճար 50 000 ֏-ից'],
        'pickup'  => ['Վերցնել խանութից', 'Այսօր՝ 18:00-ից · Աբովյան 1'],
        'region'  => ['Առաքում մարզեր', '2–3 օր · 1 000–3 000 ֏՝ ըստ քաշի'],
    ];

    public const PAYMENT = [
        'cash' => ['Կանխիկ', 'Առաքիչին կամ խանութում'],
        'card' => ['Քարտով', 'Առաքիչի տերմինալով'],
    ];

    public const REGIONS = [
        'Երևան', 'Արագածոտն', 'Արարատ', 'Արմավիր', 'Գեղարքունիք', 'Կոտայք',
        'Լոռի', 'Շիրակ', 'Սյունիք', 'Տավուշ', 'Վայոց ձոր',
    ];

    public function authorize(): bool
    {
        return true;                    // հյուրը նույնպես կարող է պատվիրել
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:2', 'max:100'],
            'phone'         => ['required', 'string', 'max:30', 'regex:/^(\+374|0)\s?\d{2}([\s\-]?\d){6}$/'],
            'email'         => ['nullable', 'email', 'max:190'],
            'delivery'      => ['required', Rule::in(array_keys(self::DELIVERY))],
            'region'        => ['exclude_if:delivery,pickup', 'required', Rule::in(self::REGIONS)],
            'address'       => ['exclude_if:delivery,pickup', 'required', 'string', 'min:5', 'max:255'],
            'payment'       => ['required', Rule::in(array_keys(self::PAYMENT))],
            'comment'       => ['nullable', 'string', 'max:500'],
            'agree'         => ['accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'անունը',
            'phone'         => 'հեռախոսը',
            'email'         => 'էլ. հասցեն',
            'delivery'      => 'առաքումը',
            'region'        => 'մարզը',
            'address'       => 'հասցեն',
            'payment'       => 'վճարումը',
            'comment'       => 'մեկնաբանությունը',
            'agree'         => 'պայմանները',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'     => 'Հեռախոսը՝ +374 XX XXXXXX ձևաչափով',
            'agree.accepted'  => 'Պետք է համաձայնել պայմաններին',
        ];
    }
}
