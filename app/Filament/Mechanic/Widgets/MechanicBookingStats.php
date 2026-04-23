<?php

namespace App\Filament\Mechanic\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MechanicBookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        $mechanicId = Auth::id();

        $baseQuery = Booking::query()
            ->where('mechanic_id', $mechanicId);

        $totalAssigned = (clone $baseQuery)->count();

        $inProgress = (clone $baseQuery)
            ->whereIn('status', ['checking', 'processing'])
            ->count();

        $waitingSparepart = (clone $baseQuery)
            ->where('status', 'waiting_sparepart')
            ->count();

        $finished = (clone $baseQuery)
            ->where('status', 'finished')
            ->count();

        return [
            Stat::make('Total Booking Saya', $totalAssigned)
                ->description('Semua pekerjaan yang ditugaskan')
                ->color('primary'),

            Stat::make('Sedang Dikerjakan', $inProgress)
                ->description('Dicek / Dikerjakan')
                ->color('warning'),

            Stat::make('Menunggu Sparepart', $waitingSparepart)
                ->description('Butuh sparepart')
                ->color('danger'),

            Stat::make('Selesai', $finished)
                ->description('Riwayat pekerjaan selesai')
                ->color('success'),
        ];
    }
}