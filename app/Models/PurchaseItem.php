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
     * Logic untuk menghitung total harga dari seluruh item purchase dan mengurangi stock
     */

   protected static function booted() : void
    {
        static::saving(function (PurchaseItem $item) {
            $item->subtotal = (float) $item->qty * (float) $item->price;

            $sparepart = $item->sparepart()->lockForUpdate()->first();

            if (! $sparepart) {
                return;
            }

            if ($item->exists) {
                $original = $item->getOriginal();
                $diffQty = $item->qty - $original['qty'];

                if ($diffQty !== 0) {
                    if ($sparepart->stock < $diffQty && $diffQty > 0) {
                        throw new \Exception('Stok sparepart tidak cukup untuk update transaksi.');
                    }

                    $sparepart->stock -= $diffQty;
                    if ($sparepart->stock < 0) {
                        $sparepart->stock = 0;
                    }
                    $sparepart->save();
                }
            } else {
                if ($sparepart->stock < $item->qty) {
                    throw new \Exception('Stok sparepart tidak cukup untuk transaksi.');
                }

                $sparepart->stock -= $item->qty;
                $sparepart->save();
            }
        });

        static::deleted(function (PurchaseItem $item) {
            $sparepart = $item->sparepart()->lockForUpdate()->first();
            if ($sparepart) {
                $sparepart->stock += $item->qty;
                $sparepart->save();
            }

            $purchase = $item->purchase;
            if ($purchase) {
                $purchase->total_amount = $purchase->items()->sum('subtotal');
                $purchase->save();
            }
        });

        static::saved(function (PurchaseItem $item) {
            $purchase = $item->purchase;

            if ($purchase) {
                $purchase->total_amount = $purchase->items()->sum('subtotal');
                $purchase->save();
            }
        });
    }
}
