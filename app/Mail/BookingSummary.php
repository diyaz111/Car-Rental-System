<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSummary extends Mailable
{
    use SerializesModels;

    public $totalBookings;
    public $bookingsByStatus;
    public $totalRevenue;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($totalBookings, $bookingsByStatus, $totalRevenue)
    {
        $this->totalBookings = $totalBookings;
        $this->bookingsByStatus = $bookingsByStatus;
        $this->totalRevenue = $totalRevenue;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Summary')
                    ->view('emails.booking_summary');
    }
}
