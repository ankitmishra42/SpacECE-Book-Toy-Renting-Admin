<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Store;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $services = Service::factory(5)->create();

        $stors = Store::all();

        foreach ($stors as $stor) {
            $randService = $faker->randomElements($services, rand(2, 5));
            foreach ($randService as $service) {
                $stor->services()->attach($service);
            }
        }
    }
}
