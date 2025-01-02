<?php

namespace Database\Seeders;

use App\Models\Additional;
use App\Repositories\ServiceRepository;
use App\Repositories\StoreRepository;
use Illuminate\Database\Seeder;

class AdditionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = (new ServiceRepository())->getAll();

        $storeIds = (new StoreRepository())->getAll()->pluck('id')->toArray();
        foreach ($services as $service) {
            for ($i = 0; $i < rand(3, 7); $i++) {
                Additional::factory()->create([
                    'service_id' => $service->id,
                    'store_id' => fake()->randomElement($storeIds),
                ]);
            }
        }
    }
}
