<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'total_price',
    ];

    public function cars()
    {
        return $this->belongsTo(Cars::class, 'car_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
