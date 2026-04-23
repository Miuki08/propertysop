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
     * Generate SKU By system
     */

    protected static function booted(): void
    {
        static::creating(function (Sparepart $sparepart) {
            if (empty($sparepart->code)) {

                $lastId = Sparepart::max('id') ?? 0;
                $nextNumber = $lastId + 1;

                $sparepart->code = 'SP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }

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

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_spareparts')->withPivot(['qty', 'price', 'cost_price', 'subtotal']);
    }

    public function purchases() {
        return $this->belongsToMany(Purchase::class, 'purchase_items')->withPivot(['qty', 'price', 'subtotal'])->withTimestamps();
    }

}
