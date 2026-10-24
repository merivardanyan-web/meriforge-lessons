<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/** 12 պատահական ապրանք էջավորումը ստուգելու համար — PHP Դաս 20 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Աքսեսուար ' . fake()->unique()->bothify('??-###');

        return [
            'name'     => $name,
            'slug'     => slugify($name),
            'specs'    => fake()->randomElement(['USB-C', 'Bluetooth', 'Wi-Fi', 'մետաղե իրան']),
            'price'    => fake()->numberBetween(30, 900) * 100,
            'discount' => fake()->randomElement([0, 0, 0, 10, 20]),
            'stock'    => fake()->numberBetween(0, 20),
            'weight'   => fake()->numberBetween(50, 2000),
            'image'    => 'placeholder.jpg',
        ];
    }
}
