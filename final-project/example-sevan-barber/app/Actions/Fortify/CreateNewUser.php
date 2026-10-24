<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

// fortify:install-ի ստեղծած ֆայլը․ հեռախոս + հայերեն անուններ։ Գրանցվողը միշտ client է ⭐
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        $input['email'] = mb_strtolower(trim($input['email'] ?? ''));

        Validator::make($input, [
            'name'     => ['required', 'string', 'min:2', 'max:60'],
            'email'    => ['required', 'string', 'email', 'max:190', Rule::unique(User::class)],
            'phone'    => ['required', 'regex:/^\+374\s?\d{2}\s?\d{6}$/'],
            'password' => $this->passwordRules(),
        ], [
            'phone.regex' => 'Հեռախոսը՝ +374 XX XXXXXX ձևաչափով',
        ], [
            'name'     => 'անունը',
            'email'    => 'էլ. հասցեն',
            'phone'    => 'հեռախոսը',
            'password' => 'գաղտնաբառը',
        ])->validate();

        // role-ը չենք փոխանցում․ բազայի default-ը՝ client
        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'phone'    => $input['phone'],
            'password' => $input['password'],
        ]);
    }
}
