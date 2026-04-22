<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'purchase_date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function items() {
        return $this->hasMany(PurchaseItem::class);
    }

    public function spareparts() {
        return $this->belongsToMany(Sparepart::class, 'purchase_items')->wherePivot(['qty', 'price', 'subtotal'])->withTimestamps();
    }
}
