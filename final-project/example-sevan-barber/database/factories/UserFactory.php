<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** Հաճախորդներ՝ հայերեն անուններով։ role-ը բազայի default-ն է (client) */
class UserFactory extends Factory
{
    protected static ?string $password;

    private const FIRST = ['Դավիթ', 'Տիգրան', 'Նարեկ', 'Հայկ', 'Սամվել', 'Էրիկ', 'Վահե', 'Արթուր', 'Կարեն', 'Ռուբեն', 'Միքայել', 'Գագիկ'];
    private const LAST = ['Մարտիրոսյան', 'Սարգսյան', 'Ավետիսյան', 'Վարդանյան', 'Խաչատրյան', 'Հովհաննիսյան', 'Մկրտչյան', 'Ղազարյան'];

    public function definition(): array
    {
        return [
            'name'              => fake()->randomElement(self::FIRST) . ' ' . fake()->randomElement(self::LAST),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => '+374 ' . fake()->randomElement(['91', '93', '94', '77', '55', '99']) . ' ' . fake()->numerify('######'),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }
}
