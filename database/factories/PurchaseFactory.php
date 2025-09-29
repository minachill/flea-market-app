<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => $this->faker->randomElement(['card', 'konbini']),
            'shipping_postal' => $this->faker->numerify('###-####'),
            'shipping_address' => $this->faker->address(),
            'shipping_building' => $this->faker->secondaryAddress(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}