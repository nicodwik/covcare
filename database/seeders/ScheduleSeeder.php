<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schedule::insert([
            [
                'date' => now(),
                'time' => '12.00 - 15.00',
                'event_id' => 1,
                'address' => 'Mabes Polri, Jln. Kapten Tendean',
                'vaccine' => 'AstraZenecca',
                'stock' => 1000
            ],
            [
                'date' => now()->addDays(1),
                'time' => '12.00 - 15.00',
                'event_id' => 1,
                'address' => 'Mabes Polri, Jln. Kapten Tendean',
                'vaccine' => 'AstraZenecca',
                'stock' => 1000
            ],
            [
                'date' => now()->addDays(2),
                'time' => '12.00 - 15.00',
                'event_id' => 1,
                'address' => 'Mabes Polri, Jln. Kapten Tendean',
                'vaccine' => 'AstraZenecca',
                'stock' => 1000
            ],
        ]);
    }
}
