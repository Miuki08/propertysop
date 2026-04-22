<?php

namespace App\Filament\Mechanic\Resources\BookingResource\Pages;

use App\Filament\Mechanic\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
}
