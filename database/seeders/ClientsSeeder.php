<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('clients')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => ('123456'), // You might want to use a more secure password hashing method
                'phone' => $faker->randomNumber(),
                'location' => $faker->address,
                'photo' => $faker->imageUrl(),
                'verification_token' => $faker->uuid,
                // Add other columns and their values here
            ]);
        }
    }
}
