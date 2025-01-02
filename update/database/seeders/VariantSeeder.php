<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Variant;
use Faker\Factory;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $stores = Store::all();

        foreach ($stores as $store) {
            $services = $store->services;
            foreach ($services as $service) {
                $variants = $faker->randomElements(config('enums.variants'), rand(2, 5));
                foreach ($variants as $variantName) {
                    Variant::create([
                        'service_id' => $service->id,
                        'store_id' => $store->id,
                        'name' => $variantName,
                    ]);
                }
            }
        }
    }
}
