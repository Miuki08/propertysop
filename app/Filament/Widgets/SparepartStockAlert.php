<?php

namespace App\Filament\Widgets;

use App\Models\Sparepart;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlySparepartSalesExport;

class SparepartStockAlert extends BaseWidget
{
    protected static ?string $heading = 'Spareparts Low Stock';
    protected int|string|array $columnSpan = [
        'md' => 2,
    ];
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sparepart::query()
                    ->where('stock', '<', 10)
                    ->orderBy('stock')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Sparepart')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->color(fn ($state) => $state <= 3 ? 'danger' : 'warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('downloadReport')
                    ->label('Download Monthly Report')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(
                            new MonthlySparepartSalesExport(now()->year, now()->month),
                            'sparepart-sales-'.now()->format('Y-m').'.xlsx'
                        );
                    }),
            ]);
    }
}
