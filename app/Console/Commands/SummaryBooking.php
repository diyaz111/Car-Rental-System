<?php

namespace App\Console\Commands;

use App\Mail\BookingStatisticsSummary;
use App\Mail\BookingSummary;
use App\Models\Bookings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SummaryBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary:booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Summary Bookings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $totalBookings = Bookings::count();

        $bookingsByStatus = Bookings::select('status', \DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $totalRevenue = Bookings::where('status', 'completed')->sum('total_price');

        // Log the results
        Log::info('Booking Statistics:', [
            'totalBookings' => $totalBookings,
            'bookingsByStatus' => $bookingsByStatus,
            'totalRevenue' => $totalRevenue,
        ]);

        Mail::to('superadmin@carrental.com')->send(new BookingSummary($totalBookings, $bookingsByStatus, $totalRevenue));

        $this->info('Booking statistics calculated, logged, and emailed.');


        return Command::SUCCESS;
    }
}
