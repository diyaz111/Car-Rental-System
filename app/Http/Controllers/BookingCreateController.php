<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingCreateRequest;
use App\Jobs\SendBookingStatusNotification;
use App\Models\Bookings;
use App\Models\Cars;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingCreateController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $bookingsSummary = Bookings::select(
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as booking_complete'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as booking_pending'),
            DB::raw('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as booking_confirmed'),
            DB::raw('SUM(CASE WHEN status = "canceled" THEN 1 ELSE 0 END) as booking_canceled'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN total_price ELSE 0 END) as total_revenue')
        )->first();
        $totalBookings = $bookingsSummary->total_bookings ?? 0;
        $bookingComplete = $bookingsSummary->booking_complete ?? 0;
        $bookingPending = $bookingsSummary->booking_pending ?? 0;
        $bookingConfirmed = $bookingsSummary->booking_confirmed ?? 0;
        $bookingCanceled = $bookingsSummary->booking_canceled ?? 0;
        $totalRevenue = $bookingsSummary->total_revenue ?? 0;

        return view('booking.index', compact('totalRevenue', 'totalBookings', 'bookingComplete', 'bookingPending', 'bookingConfirmed', 'bookingCanceled', 'user'));
    }

    public function create()
    {
        $cars = Cars::where('availability_status', 'available')->get();
        $user = Auth::user()->id;

        return view('booking.create', compact('cars', 'user'));
    }

    public function store(BookingCreateRequest $request)
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return back()->withErrors(['user_id' => 'The user does not exist.']);
        }

        DB::beginTransaction();
        try {
            $car = Cars::findOrFail($request->car_id);
            $startDate = Carbon::parse($request->startdate);
            $endDate = Carbon::parse($request->enddate);
            $days = $startDate->diffInDays($endDate);
            $calculatedTotalPrice = $days * $car->price_per_day;
            $user = User::findOrFail($request->user_id);

            if ($request->total_price != $calculatedTotalPrice) {
                return back()->withErrors(['total_price' => 'The total price calculation is incorrect.']);
            }

            $existingBooking = Bookings::where('car_id', $request->car_id)
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
                return back()->withErrors(['error' => 'The car is already booked for the selected dates.']);
            }

            Bookings::create([
                'user_id' => $request->user_id,
                'car_id' => $request->car_id,
                'start_date' => $request->startdate,
                'end_date' => $request->enddate,
                'total_price' => $request->total_price,
                'status' => 'pending',
            ]);

            $dataBooking = [
                "userName" => $user->name,
                "userEmail" => $user->email,
                "user_id" => $request->user_id,
                "carName" => $car->nama,
                "brand" => $car->brand,
                "startDate" => $request->startdate,
                "endDate" => $request->enddate,
                "totalDays" => $days,
                "totalPrice" => $request->total_price,
                "status" => "pending",
            ];


            session(['dataBooking' => $dataBooking]);

            $car->update([
                'availability_status' => 'booked'
            ]);
            DB::commit();
            SendBookingStatusNotification::dispatch($dataBooking);
            return redirect()->route('booking.details')->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while creating the booking: ' . $e->getMessage()]);
        }
    }

    public function showBookingDetails()
    {
        return view('booking.details');
    }

    public function datatableAdmin()
    {
        $data = Bookings::with(['cars', 'users']) // Assuming you have a relationship set up
            ->get()
            ->map(function ($booking) {
                return [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'username' => $booking->users->name,
                    'car_id' => $booking->car_id,
                    'car_nama' => $booking->cars->nama, // Assuming the car model has a 'name' field
                    'car_brand' => $booking->cars->brand, // Assuming the car model has a 'brand' field
                    'start_date' => $booking->start_date,
                    'end_date' => $booking->end_date,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                ];
            });

        return response()->json(['status' => true, 'message' => 'OK', 'data' => $data]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);
        $booking = Bookings::findOrFail($id);

        $car = Cars::where('id', $booking->car_id)->first();
        $booking->update([
            'status' => $request->status
        ]);
        $startDate = Carbon::parse($booking->start_date);
        $endDate = Carbon::parse($booking->endDate);
        $days = $startDate->diffInDays($endDate);

        $dataUpdate = [
            "userName" => Auth::user()->name,
            "userEmail" => Auth::user()->email,
            "user_id" => Auth::user()->id,
            "carName" => $car->nama,
            "brand" => $car->brand,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "totalDays" => $days,
            "totalPrice" => $booking->total_price,
            "status" => $request->status
        ];


        SendBookingStatusNotification::dispatch($dataUpdate);

        return response()->json(['message' => 'Status updated successfully!']);
    }
}
