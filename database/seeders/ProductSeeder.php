<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\Variant;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        foreach (Store::all() as $store) {
            foreach ($store->services as $service) {
                Product::factory(rand(1, 5))->create([
                    'service_id' => $service->id,
                    'variant_id' => $faker->randomElement(Variant::all()),
                    'store_id' => $store->id,
                ]);
            }
        }
    }
}
