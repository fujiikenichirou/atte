<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;
use Faker\Factory as Faker;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        for ($i = 1; $i <= 100; $i++) {
            $startTime = $faker->dateTimeBetween('today 9:00', 'today 10:00');
            $endTime = $faker->dateTimeBetween('today 17:00', 'today 19:00');

            Work::create([
                'user_id' => $faker->numberBetween(1, 100),
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);
        }

        for ($i = 1; $i <= 100; $i++) {
            $startTime = $faker->dateTimeBetween('yesterday 9:00', 'yesterday 10:00');
            $endTime = $faker->dateTimeBetween('yesterday 17:00', 'yesterday 19:00');

            Work::create([
                'user_id' => $faker->numberBetween(1, 100),
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);
        }
    }
}
