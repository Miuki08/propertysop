<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'address'];

    public function bookings()  {
        return $this->hasMany(Booking::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
