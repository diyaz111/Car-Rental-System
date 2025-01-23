<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
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

        return view('home', compact('totalRevenue','totalBookings', 'bookingComplete', 'bookingPending', 'bookingConfirmed', 'bookingCanceled'));
    }

    public function datatable()
    {
        $data = Cars::where('availability_status', 'available')->get();

        return response()->json(['status' => true, 'message' => 'OK', 'data' => $data]);
    }
}
