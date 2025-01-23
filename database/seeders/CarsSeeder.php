<?php

namespace Database\Seeders;

use App\Models\Cars;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $cars = [];

        for ($i = 0; $i < 1000; $i++) {
            $cars[] = [
                'nama' => $faker->company,
                'brand' => $faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Audi', 'Chevrolet']),
                'price_per_day' => $faker->randomFloat(2, 300000, 1000000),
                'availability_status' => $faker->randomElement(['available', 'booked']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Cars::insert($cars);
    }
}
