<?php

namespace App\Exports;

use App\Models\Sparepart;
use App\Models\BookingSparepart;
use App\Models\PurchaseItem;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlySparepartSalesExport implements FromCollection
{
    public function __construct(
        protected int $year,
        protected int $month,
    ){}
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $fromBookings = BookingSparepart::with('sparepart', 'booking')
            ->whereHas('booking', fn ($q) => $q->whereBetween('booking_date', [$start, $end]))
            ->get()
            ->map(function ($row) {
                return [
                    'source' => 'service',
                    'date' => $row->booking->booking_date,
                    'sparepart' => $row->sparepart?->name,
                    'qty' => $row->qty,
                    'price' => $row->price,
                    'subtotal' => $row->qty * $row->price,
                ];
            });

        $fromPurchases = PurchaseItem::with('sparepart', 'purchase')
            ->whereHas('purchase', fn ($q) => $q->whereBetween('purchase_date', [$start, $end]))
            ->get()
            ->map(function ($row) {
                return [
                    'source' => 'purchase',
                    'date' => $row->purchase->purchase_date,
                    'sparepart' => $row->sparepart?->name,
                    'qty' => $row->qty,
                    'price' => $row->price,
                    'subtotal' => $row->subtotal,
                ];
            });

        return $fromBookings->concat($fromPurchases);
    }

    public function headings(): array
    {
        return [
            'Source',
            'Date',
            'Sparepart',
            'Qty',
            'Price',
            'Subtotal',
        ];
    }
}
