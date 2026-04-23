<?php

namespace App\Filament\Mechanic\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MechanicFinishedBookings extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Booking::query()
            ->where('mechanic_id', Auth::id())
            ->where('status', 'finished')
            ->latest('finished_at');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('finished_at')
                ->label('Selesai')
                ->dateTime('d/m/Y H:i')
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
                ->formatStateUsing(fn () => 'Selesai')
                ->color('success'),
        ];
    }

    protected function getTableHeading(): ?string
    {
        return 'Booking yang Sudah Selesai';
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