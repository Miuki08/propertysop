<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Scalar\Float_;

class BookingSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'sparepart_id',
        'qty',
        'cost_price',
        'price',
        'subtotal',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function sparepart() {
        return $this->belongsTo(Sparepart::class);
    }

    public function getSubtotalAttribute(): float {
        return (float) $this->qty * (float) $this->price;
    }
}
