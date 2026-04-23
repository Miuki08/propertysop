<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'service_fee',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'finished_at' => 'datetime',
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

}
