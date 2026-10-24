<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

// fortify:install-ի ստեղծած ֆայլը՝ հայերեն անուններով։ role-ը չենք վերցնում ⭐ (Դաս 16, տնային 10) — PHP Դաս 21
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        $input['email'] = mb_strtolower(trim($input['email'] ?? ''));

        Validator::make($input, [
            'name'     => ['required', 'string', 'min:2', 'max:60'],
            'email'    => ['required', 'string', 'email', 'max:190', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
        ], [], [
            'name'     => 'անունը',
            'email'    => 'էլ. հասցեն',
            'password' => 'գաղտնաբառը',
        ])->validate();

        // Միայն երեք դաշտ․ $fillable-ում role չկա, ուստի role=admin-ը կանտեսվեր նույնիսկ create($input)-ով
        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => $input['password'],       // 'hashed' cast-ը կհեշավորի
        ]);
    }
}
