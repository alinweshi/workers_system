<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('orders')->insert([
                'post_id' => $faker->numberBetween(104, 107),
                'post_title' => $faker->sentence(5),
                'worker_id' => $faker->numberBetween(44, 54),
                'client_id' => $faker->numberBetween(24, 32),
                // Add other columns and their values here
            ]);
        }
    }
}
