<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Faker\Factory;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        AppSetting::updateOrCreate([
            'name' => 'Laundry Mart',
            'title' => 'laundryMart Admin Dashboard',
            'logo' => null,
            'fav_icon' => null,
            'signature_id' => null,
            'city' => $faker->city(),
            'address' => $faker->address(),
            'road' => $faker->randomDigitNotZero(),
            'area' => $faker->city(),
            'mobile' => $faker->phoneNumber(),
            'currency' => '$',
        ]);
    }
}
