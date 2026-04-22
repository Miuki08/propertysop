<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'sparepart_id',
        'qty',
        'price',
        'subtotal',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    /**
     * Logic untuk menghitung total harga dari seluruh item purchase
     */

    protected static function booted() : void {
        static::saved(function (PurchaseItem $item) {
            $purchase = $item->purchase;

            if ($purchase) {
                $purchase->total_amount = $purchase->items()->sum('subtotal');
                $purchase->save();
            }
        });

        static::deleted(function (PurchaseItem $item) {
            $purchase = $item->purchase;

            if ($purchase) {
                $purchase->total_amount = $purchase->items()->sum('subtotal');
                $purchase->save();
            }
        });
    }
}
