<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Cars;
use Carbon\Carbon;

class BookingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bookings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate random start and end dates
        $startDate = Carbon::now()->addDays(rand(1, 10));
        $endDate = (clone $startDate)->addDays(rand(1, 5));

        return [
            'user_id' => User::factory(), // Creates a new user
            'car_id' => Cars::factory(), // Creates a new car
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_price' => $this->faker->numberBetween(500000, 5000000), // Random price
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'canceled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
