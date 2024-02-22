<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkerReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 4) as $index) {
            DB::table('worker_reviews')->insert([
                'post_id' => $faker->numberBetween(104,107),
                'client_id' => $faker->numberBetween(24, 36),
                'comment' => $faker->realText($maxNbChars = 200, $indexSize = 2),
                'rate' => $faker->numberBetween(1, 5),
                // Add other columns and their values here
            ]);
        }
    }
}
