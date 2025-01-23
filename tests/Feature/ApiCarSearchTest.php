<?php

namespace Tests\Feature;

use App\Models\Cars;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiCarSearchTest extends TestCase
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
    public function test_it_returns_cached_data_for_price_range_and_availability_status()
    {
        $postData = [
            'price_range' => '1000000-5000000',
            'availability_status' => 'unavailable',
        ];
        $response = $this->postJson('/api/cars/search', $postData, $this->headers);

        $response->assertStatus(404);
        $response->assertJson([
            'data' => [
                'message' => 'No cars found matching your filters.',
            ],
        ]);
    }


    /** @test */
    public function it_returns_cached_data_when_available()
    {
        $postData = [
            'brand' => 'BMW',
        ];

        $cacheKey = 'car_search_' . md5(json_encode($postData));

        $cachedData = collect([
            (object) [
                'id' => 5,
                'nama' => 'Jerde-Stamm',
                'brand' => 'Ford',
                'price_per_day' => 776442,
                'availability_status' => 'available',
            ],
        ])->map(function ($item) {
            return (array) $item;
        });

        Cache::shouldReceive('get')
            ->once()
            ->with($cacheKey)
            ->andReturn($cachedData);


        $this->headers = [
            'Authorization' => 'Bearer ' . $this->generateJwtTokenForUser($this->user),
        ];

        $response = $this->postJson('/api/cars/search', $postData, $this->headers);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Cars retrieved successfully.',
            'data' => $cachedData->toArray(),
        ]);
    }


    protected function generateJwtTokenForUser(User $user)
    {
        return JWTAuth::fromUser($user);
    }
}
