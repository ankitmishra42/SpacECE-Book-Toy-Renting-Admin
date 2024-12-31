<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Store;
use App\Models\Transaction;
use Faker\Factory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderProduct::query()->delete();
        Transaction::query()->delete();
        Rating::query()->delete();
        Order::query()->delete();

        $customrs = Customer::all();
        $faker = Factory::create();
        $min = ['00', 15, 30, 45];
        $stores = Store::all();

        foreach ($customrs as $key => $customr) {
            for ($i = 0; $i < rand(1, 10); $i++) {
                $store = $faker->randomElement($stores);
                $coupons = $store->coupons;
                $coupon = $faker->randomElement($coupons);

                $ordr = Order::factory()->create([
                    'customer_id' => $customr->id,
                    'store_id' => $store->id,
                    'coupon_id' => $faker->randomElement($coupons)?->id,
                    'discount' => $coupon?->discount ?? 0,
                    'pick_hour' => rand(0, 23).':'.$faker->randomElement($min).':00',
                    'delivery_hour' => rand(0, 23).':'.$faker->randomElement($min).':00',
                ]);

                Transaction::factory()->create([
                    'wallet_id' => $store->user?->wallet?->id,
                    'store_id' => $store->id,
                    'order_id' => $ordr->id,
                    'amount' => $ordr->total_amount,
                ]);
            }
        }

        $orders = Order::all();
        $producs = Product::isActive()->get();

        foreach ($orders as $order) {
            foreach ($faker->randomElements($producs, rand(2, 10)) as $product) {
                $order->products()->attach($product->id);
            }
        }
    }
}
