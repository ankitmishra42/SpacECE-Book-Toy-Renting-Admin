<?php

namespace Database\Seeders;

use App\Models\OrderSchedule;
use App\Repositories\StoreRepository;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ((new StoreRepository())->getAll() as $store) {
            if ($store->schedules->isEmpty()) {
                foreach ($days as $day) {
                    OrderSchedule::create([
                        'store_id' => $store->id,
                        'day' => $day,
                        'start_time' => 8,
                        'end_time' => 16,
                        'per_hour' => 1,
                        'is_active' => true,
                        'type' => 'pickup',
                    ]);
                }

                foreach ($days as $day) {
                    OrderSchedule::create([
                        'store_id' => $store->id,
                        'day' => $day,
                        'start_time' => 8,
                        'end_time' => 16,
                        'per_hour' => 1,
                        'is_active' => true,
                        'type' => 'delivery',
                    ]);
                }
            }
        }
    }
}
