<?php

namespace Tests\Feature;

use App\Http\Requests\BookingCreateRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Cars;
use App\Models\User;
use App\Models\Bookings;
use App\Jobs\SendBookingStatusNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Mockery;

class BookingValidateTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); // Assuming you have a User factory set up
    }

    public function testStoreBookingFailsWhenCarAlreadyBooked()
    {
       // Arrange: Create a car and a user using the factory
       $car = Cars::factory()->create();
       $user = User::factory()->create();  // Make sure to create the user for login

       // Log in the user
       $this->actingAs($user);

       // Simulate an existing booking for the same car with the same dates
       Bookings::create([
           'user_id' => $user->id,
           'car_id' => $car->id,
           'start_date' => '2025-01-24',
           'end_date' => '2025-01-30',
           'total_price' => 500,
           'status' => 'pending',
       ]);

       // Act: Make a POST request with the same dates for the same car
       $response = $this->post(route('booking.store'), [
           'car_id' => $car->id,
           'user_id' => $user->id,
           'startdate' => '2025-01-24',
           'enddate' => '2025-01-30',
           'total_price' => 500,
       ]);

       $response->assertSessionHasErrors('total_price');
       $response->assertSessionHasErrors(['total_price' => 'The total price calculation is incorrect.']);

    }

    public function test_booking_creation_successfully()
    {
        // Arrange
        $user = User::factory()->create(); // Create a dummy user
        $car = Cars::factory()->create(['price_per_day' => 100]); // Create a dummy car with a price per day
        $this->actingAs($user);
        // Set up the request data
        $request = [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'startdate' => Carbon::today()->format('Y-m-d'),
            'enddate' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'total_price' => 500, // Correct total price (100 * 5 days)
        ];

        // Act
        $response = $this->post(route('booking.store'), $request);

        // Assert
        $response->assertRedirect(route('booking.details')); // Check if it redirects to the booking details
        $response->assertSessionHas('success', 'Booking created successfully!'); // Check for success session message

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'total_price' => 500,
            'status' => 'pending',
        ]); // Check if the booking was inserted into the database

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'availability_status' => 'booked',
        ]); // Check if the car's availability status is updated to booked
    }

    public function tearDown(): void
    {
        // Delete the user after the test is finished
        $this->user->delete();

        parent::tearDown();
    }
}
