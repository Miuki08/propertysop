<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Booking;
use Carbon\Carbon;

class BookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $bookingsThisMonth = Booking::whereBetween('booking_date', [$startOfMonth, $endOfMonth])->count();
        $bookingsToday = Booking::whereDate('booking_date', $today)->count();
        $completedToday = Booking::whereDate('finished_at', $today)->where('status', 'completed')->count();
        $inProgressToday = Booking::whereDate('booking_date', $today)->where('status', 'in_progress')->count();
        return [
            Stat::make('Bookings this month', $bookingsThisMonth),
            Stat::make('Bookings today', $bookingsToday),
            Stat::make('Completed today', $completedToday),
            Stat::make('In Progress today', $inProgressToday),
        ];
    }
}
