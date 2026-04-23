<?php

namespace App\Filament\Mechanic\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingSparepartsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingSpareparts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sparepart_id')
                    ->label('Spareparts')
                    ->options(
                        Sparepart::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()        
                    ->dehydrated(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sparepart_id')
            ->columns([
                Tables\Columns\TextColumn::make('sparepart.name')
                    ->label('Sparepart')
                    ->searchable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->formatStateUsing(fn ($record) => $record->qty * $record->price)
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $sparepart = Sparepart::find($data['sparepart_id']);

                        if ($sparepart) {
                            $data['price'] = $sparepart->price;
                            $data['cost_price'] = $sparepart->price;
                            $data['subtotal'] = $data['qty'] * $data['price'];
                        }

                        return $data;
                    })
                    ->using(function (CreateAction $action, array $data) {
                        $sparepart = Sparepart::findOrFail($data['sparepart_id']);

                        $booking = $this->getOwnerRecord();
                        $data['booking_id'] = $booking->id;

                        if ($sparepart->stock < $data['qty']) {
                            $booking->status = 'waiting_sparepart';
                            $booking->save();

                            $action->failureNotificationTitle('Stok tidak cukup, servis di-set Menunggu Sparepart.');
                            $action->halt();

                            return null;
                        }

                        $sparepart->stock -= $data['qty'];
                        $sparepart->save();

                        return BookingSparepart::create($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $sparepart = Sparepart::find($data['sparepart_id']);

                        if ($sparepart) {
                            $data['price'] = $sparepart->price;
                            $data['cost_price'] = $sparepart->price;
                            $data['subtotal'] = $data['qty'] * $data['price'];
                        }

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
