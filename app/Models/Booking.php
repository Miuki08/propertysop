<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'mechanic_id',
        'device_type',
        'device_brand',
        'device_model',
        'problem_description',
        'status',
        'booking_date',
        'finished_at',
        'service_warranty_until',
        'service_fee',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'finished_at' => 'datetime',
        'service_warranty_until'   => 'date',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function mechanic() {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function bookingSpareparts() {
        return $this->hasMany(BookingSparepart::class);
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'booking_spareparts')->withPivot(['qty', 'price', 'cost_price', 'subtotal']);
    }

    protected static function booted(): void
    {
        static::saving(function (Booking $booking) {
            if (! $booking->finished_at) {
                return;
            }

            if (empty($booking->service_warranty_until)) {
                $base = $booking->finished_at instanceof Carbon
                    ? $booking->finished_at
                    : Carbon::parse($booking->finished_at);

                $booking->service_warranty_until = $base->copy()->addDays(30);
            }
        });
    }

}
