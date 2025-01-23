<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'cars';

    protected $fillable = [
        'nama',
        'price_per_day',
        'availability_status',
        'brand'
    ];

    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }
}
