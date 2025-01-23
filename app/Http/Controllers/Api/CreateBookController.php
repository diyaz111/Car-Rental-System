<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateRequest;
use App\Jobs\SendBookingStatusNotification;
use App\Models\Bookings;
use App\Models\Cars;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class CreateBookController extends Controller
{
    public function create(CreateRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $name = $request->name;
        $brand = $request->brand;
        $startDate = $request->startDate ? Carbon::parse($request->startDate) : null;
        $endDate = $request->endDate ? Carbon::parse($request->endDate) : null;
        $refId = Str::uuid()->toString();

        if (!$startDate || !$endDate) {
            $response = CommonHelpers::response(true, $refId, 422, 'Start date and end date are required and must be valid.');
            return response()->json($response, $response['code']);
        }

        $car = Cars::where('nama', $name)->where('brand', $brand)->first();

        if (!$car) {
            $response = CommonHelpers::response(true, $refId, 404, 'Car not found.');
            return response()->json($response, $response['code']);
        }

        $cacheKey = 'car_' . $car->id . '_booked';

        //simpan data ke dalam key yg telah dibuat
        $booking = Cache::get($cacheKey);

        // check ketersediaan mobil pada tanggal yg di request
        $existingBooking = Bookings::where('car_id', $car->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($existingBooking) {
            $response = CommonHelpers::response(true, $refId, 422, 'Sorry, the car is already booked.');
            return response()->json($response, $response['code']);
        }

        $totalDays = $startDate->diffInDays($endDate);
        $totalPrice = $car->price_per_day * $totalDays;

        Bookings::create([
            "user_id" => $user->id,
            "car_id" => $car->id,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "total_price" => $totalPrice,
            "status" => "pending",
        ]);

        $car->update([
            'availability_status' => 'booked'
        ]);

        Cache::forget($booking);

        $data = [
            "userName" => $user->name,
            "userEmail" => $user->email,
            "carName" => $car->nama,
            "brand" => $car->brand,
            "startDate" => Carbon::parse($startDate)->format('d-m-y'),
            "endDate" => Carbon::parse($endDate)->format('d-m-y'),
            "totalDays" => $totalDays,
            "totalPrice" => $totalPrice,
            "status" => "pending",
        ];

        // send notification
        SendBookingStatusNotification::dispatch($data);

        $response = CommonHelpers::response(true, $refId, 200, 'Car successfully booked.', $data);
        return response()->json($response, $response['code']);
    }
}
