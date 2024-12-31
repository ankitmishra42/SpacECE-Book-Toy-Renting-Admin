<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Repositories\StoreRepository;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = (new StoreRepository())->getAll();
        foreach ($stores as $store) {
            Coupon::factory(rand(10, 15))->create([
                'store_id' => $store->id,
            ]);
        }
    }
}
