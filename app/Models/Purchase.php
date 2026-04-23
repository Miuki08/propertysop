<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'purchase_date',
        'total_amount',
        'sparepart_warranty_until',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'sparepart_warranty_until' => 'date',
    ];

    /**
     * Relasi data 
     */

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(PurchaseItem::class);
    }

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'purchase_items')->withPivot(['qty', 'price', 'subtotal'])->withTimestamps();
    }

    /**
     * logic garansi dari barang
     */

    protected static function booted(): void
    {
        static::creating(function (Purchase $purchase) {
            $baseDate = $purchase->purchase_date
                ? Carbon::parse($purchase->purchase_date)
                : Carbon::now();

            if (empty($purchase->sparepart_warranty_until)) {
                $purchase->sparepart_warranty_until = $baseDate->copy()->addYear();
            }
        });
    }
}
