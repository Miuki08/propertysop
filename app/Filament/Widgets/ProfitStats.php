<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\BookingSparepart;
use App\Models\PurchaseItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfitStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSparepartService = BookingSparepart::sum('subtotal');
        $totalSparepartRetail = PurchaseItem::sum('subtotal');

        $totalPenjualanSparepart = $totalSparepartService + $totalSparepartRetail;

        $totalBiayaJasaServis = Booking::sum('service_fee');

        $totalModalSparepart = BookingSparepart::sum(\DB::raw('cost_price * qty'));

        $laba = ($totalPenjualanSparepart + $totalBiayaJasaServis) - $totalModalSparepart;

        return [
            Stat::make('Total Penjualan Sparepart', 'Rp ' . number_format($totalPenjualanSparepart, 0, ',', '.'))
                ->description('Servis dan Penjualan'),

            Stat::make('Total Biaya Jasa Servis', 'Rp ' . number_format($totalBiayaJasaServis, 0, ',', '.')),

            Stat::make('Laba Kotor', 'Rp ' . number_format($laba, 0, ',', '.'))
                ->description('Selisih Pendapatan Penjualan')
                ->color($laba >= 0 ? 'success' : 'danger'),
        ];
    }
}
