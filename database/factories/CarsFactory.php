<?php

namespace Database\Factories;

use App\Models\Cars;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarsFactory extends Factory
{
    protected $model = Cars::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'price_per_day' => $this->faker->numberBetween(100, 500),
            'availability_status' => 'available', // Or 'booked'
        ];
    }
}
