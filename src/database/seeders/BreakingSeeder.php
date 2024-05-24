<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breaking;
use Faker\Factory as Faker;

class BreakingSeeder extends Seeder
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
            // Work 100件を想定しているため、ランダムなwork_idを指定
            $workId = $faker->numberBetween(1, 100); 

            // breaking_start_time と breaking_end_time を生成
            $breakingStartTime = $faker->dateTimeBetween('today 10:00', 'today 11:00');
            $breakingEndTime = $faker->dateTimeBetween('today 11:00', 'today 12:00');

            $breakingStartTime = $faker->dateTimeBetween('today 13:00', 'today 14:00');
            $breakingEndTime = $faker->dateTimeBetween('today 14:00', 'today 15:00');

            // breaking_start_time が null の場合、breaking_end_time も null に設定
            if ($breakingStartTime === null) {
                $breakingEndTime = null;
            }

            Breaking::create([
                'work_id' => $workId,
                'breaking_start_time' => $breakingStartTime,
                'breaking_end_time' => $breakingEndTime,
            ]);
        }

        for ($i = 1; $i <= 100; $i++) {
            // Work 100件を想定しているため、ランダムなwork_idを指定
            $workId = $faker->numberBetween(1, 100); 

            // breaking_start_time と breaking_end_time を生成
            $breakingStartTime = $faker->dateTimeBetween('yesterday 10:00', 'yesterday 11:00');
            $breakingEndTime = $faker->dateTimeBetween('yesterday 11:00', 'yesterday 12:00');

            $breakingStartTime = $faker->dateTimeBetween('yesterday 13:00', 'yesterday 14:00');
            $breakingEndTime = $faker->dateTimeBetween('yesterday 14:00', 'yesterday 15:00');

            // breaking_start_time が null の場合、breaking_end_time も null に設定
            if ($breakingStartTime === null) {
                $breakingEndTime = null;
            }

            Breaking::create([
                'work_id' => $workId,
                'breaking_start_time' => $breakingStartTime,
                'breaking_end_time' => $breakingEndTime,
            ]);
        }
    }
}
