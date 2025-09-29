<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'detail' => $this->faker->sentence(),
            'brand_name' => $this->faker->word(),
            'product_condition' =>  $this->faker->numberBetween(1, 4),
            'image' => 'dummy.png',
            'is_sold' => 0,
            'user_id' => User::factory(),
        ];
    }
}