<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => 1,
            'store_id' => 1,
            'order_id' => 1,
            'amount' => 100,
            'transition_id' => $this->faker->randomDigit(),
            'purpose' => 'order add',
            'note' => $this->faker->paragraph,
        ];
    }
}
