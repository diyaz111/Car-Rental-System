<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cars;
use App\Models\Bookings;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class ApiCarBookingTest extends TestCase
{

    protected $user;
    protected $headers;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $token = $this->generateJwtTokenForUser($this->user);

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->generateJwtTokenForUser($this->user),

        ];
    }

    public function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    protected function generateJwtTokenForUser(User $user)
    {
        return JWTAuth::fromUser($user);
    }
    public function test_successful_car_booking()
    {

        $car = Cars::factory()->create([
            'nama' => 'Toyota Avanza',
            'brand' => 'Toyota',
            'price_per_day' => 500000,
        ]);

        $requestData = [
            'name' => $car->nama,
            'brand' => $car->brand,
            'startDate' => '2025-01-24 20:02:08',
            'endDate' => '2025-01-26 20:02:08',
        ];

        $response = $this->withHeaders($this->headers)->postJson('/api/booking/create', $requestData);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'OK'
        ]);
    }

    public function test_it_fails_when_car_is_already_booked()
    {
        $car = Cars::factory()->create();

        Bookings::factory()->create([
            'car_id' => $car->id,
            'start_date' => '2025-01-24',
            'end_date' => '2025-01-26',
            'status' => 'confirmed',
        ]);

        $requestData = [
            'name' => $car->nama,
            'brand' => $car->brand,
            'startDate' => '2025-01-25',
            'endDate' => '2025-01-27',
        ];

        $response = $this->withHeaders($this->headers)->postJson('/api/booking/create', $requestData);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => true,
            'message' => 'Sorry, the car is already booked.',
        ]);
    }
}
