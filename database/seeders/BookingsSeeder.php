<?php

namespace Database\Seeders;

use App\Models\Bookings;
use App\Models\Cars;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $bookings = [];

        $users = User::get();
        $cars = Cars::get();

        for ($i = 0; $i < 2000; $i++) {
            $user = $users->random();
            $car = $cars->random();

            $start_date = Carbon::parse($faker->dateTimeBetween('-1 month', '+1 month'));
            $end_date = $start_date->copy()->addDays(rand(1, 7));

            $diff = $start_date->diffInDays($end_date);
            $total_price = $car->price_per_day * $diff;

            $bookings[] = [
                'user_id' => $user->id,
                'car_id' => $car->id,
                'start_date' => $start_date->format('Y-m-d'),
                'end_date' => $end_date->format('Y-m-d'),
                'total_price' => $total_price,
                'status' => $faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Bookings::insert($bookings);
    }
}
