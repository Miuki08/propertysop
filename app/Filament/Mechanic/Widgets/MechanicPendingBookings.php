<?php

namespace App\Filament\Mechanic\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MechanicPendingBookings extends BaseWidget
{
    protected int|string|array $columnSpan = 'full'; // biar responsif, nanti diatur di layout

    protected function getTableQuery(): Builder
    {
        return Booking::query()
            ->where('mechanic_id', Auth::id())
            ->whereIn('status', ['waiting_queue', 'checking'])
            ->latest('booking_date');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('booking_date')
                ->label('Tanggal')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer')
                ->searchable(),

            Tables\Columns\TextColumn::make('device_type')
                ->label('Device')
                ->formatStateUsing(fn ($state, $record) =>
                    trim(($record->device_type ?? '') . ' ' . ($record->device_brand ?? ''))
                ),

            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'waiting_queue' => 'Menunggu Antrean',
                    'checking'      => 'Dicek',
                    default         => $state,
                })
                ->color(fn (string $state) => match ($state) {
                    'waiting_queue' => 'gray',
                    'checking'      => 'info',
                    default         => 'gray',
                }),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Booking yang Belum Dikerjakan';
    }

   protected function isTablePaginationEnabled(): bool
    {
        return true;
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }
}