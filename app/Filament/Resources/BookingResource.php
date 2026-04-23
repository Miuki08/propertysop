<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Filament\Resources\BookingResource\RelationManagers\BookingSparepartsRelationManager;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Pail\Options;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship(name: 'customer', titleAttribute: 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('mechanic_id')
                    ->label('Mechanic')
                    // ->relationship(
                    //     name: 'mechanic',
                    //     titleAttribute: 'name', 
                    // )
                    ->searchable()
                    ->options(
                        \App\Models\User::query()
                            ->where('role', 'mechanic')
                            ->pluck('name', 'id')
                    )
                    ->nullable(),
                Forms\Components\DatePicker::make('booking_date')
                    ->label('Booking Date')
                    ->default(now())
                    ->required(),
                Forms\Components\TextInput::make('device_type')
                    ->label('Type Device')
                    ->placeholder('laptop / PC / Printer')
                    ->maxLength(100),
                Forms\Components\TextInput::make('device_brand')
                    ->label('Brand Device')
                    ->maxLength(100),
                Forms\Components\TextInput::make('device_model')
                    ->label('Model Device')
                    ->maxLength(100),
                Forms\Components\Textarea::make('problem_description')
                    ->label('Problem Description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'waiting_queue'     => 'Menunggu Antrean',
                        'checking'          => 'Dicek',
                        'waiting_sparepart' => 'Menunggu Sparepart',
                        'processing'        => 'Dikerjakan',
                        'finished'          => 'Selesai',
                        'cancelled'         => 'Dibatalkan',
                    ])
                    ->default('waiting_queue')
                    ->required(),
                Forms\Components\DateTimePicker::make('finished_at')
                    ->label('Finish At')
                    ->native(false)
                    ->nullable(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('mechanic.name')
                    ->label('Mechanic')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('device_type')
                    ->label('Device')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'waiting_queue'     => 'Menunggu Antrean',
                        'checking'          => 'Dicek',
                        'waiting_sparepart' => 'Menunggu Sparepart',
                        'processing'        => 'Dikerjakan',
                        'finished'          => 'Selesai',
                        'cancelled'         => 'Dibatalkan',
                        default             => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'waiting_queue'     => 'gray',
                        'checking'          => 'info',
                        'waiting_sparepart' => 'warning',
                        'processing'        => 'primary',
                        'finished'          => 'success',
                        'cancelled'         => 'danger',
                        default             => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('problem_description')
                    ->label('Problem')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->problem_description)
                    ->searchable(),

                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Finished At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->notes)
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                 Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('booking_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('booking_date', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('booking_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingSparepartsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
