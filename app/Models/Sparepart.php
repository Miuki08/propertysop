<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'stock',
        'price',
        'description',
    ];

    /**
     * One To Many Relation
     */

    public function bookingSpareparts() {
        return $this->hasMany(BookingSparepart::class);
    }

    public function purchaseItems() {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Many to Many relation
     */

    public function bookings() {
        return $this->belongsToMany(Booking::class, 'booking_sparepart')->withPivot(['qty', 'price'])->withTimestamps();
    }

    public function purchases() {
        return $this->belongsToMany(Purchase::class, 'purchase_items')->withPivot(['qty', 'price', 'subtotal'])->withTimestamps();
    }

}
