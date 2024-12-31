<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stor>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lat = 23.768537;
        $lng = 90.352858;
        $dot = [.001, .002, .003, .004, .005, .006, .007, .008, .009, .011, .012, .013, .014, .015, .016, .017, .018, .019];

        return [
            'shop_owner' => 1,
            'name' => $this->faker->name,
            'logo_id' => Media::factory()->create(),
            'banner_id' => Media::factory()->create(),
            'description' => $this->faker->paragraph,
            'commission' => $this->faker->randomFloat(2, 1, 10),
            'status' => $this->faker->boolean(),
            'latitude' => $lat + $this->faker->randomElement($dot),
            'longitude' => $lng + $this->faker->randomElement($dot),
            'prifix' => 'MV',
        ];
    }
}
