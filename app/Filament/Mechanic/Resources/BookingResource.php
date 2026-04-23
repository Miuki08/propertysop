<?php

namespace App\Filament\Mechanic\Resources;

use App\Filament\Mechanic\Resources\BookingResource\Pages;
use App\Filament\Mechanic\Resources\BookingResource\RelationManagers\BookingSparepartsRelationManager;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Loh Bookings';
    protected static ?string $pluralLabel = 'Loh Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Info')
                    ->schema([
                        Forms\Components\TextInput::make('customer.name')
                            ->label('Customer')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('device_type')
                            ->label('Device')
                            ->disabled(),

                        Forms\Components\TextInput::make('device_brand')
                            ->label('Brand')
                            ->disabled(),

                        Forms\Components\TextInput::make('device_model')
                            ->label('Model')
                            ->disabled(),

                        Forms\Components\Textarea::make('problem_description')
                            ->label('Problem')
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(2),

                    Forms\Components\Section::make('Service Progress')
                        ->schema([
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
                                ->label('Finished At')
                                ->native(false)
                                ->nullable(),

                            Forms\Components\Textarea::make('notes')
                                ->label('Notes')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('mechanic_id', Auth::id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
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

                Tables\Columns\TextColumn::make('finished_at')
                    ->label('Finished At')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting_queue'     => 'Menunggu Antrean',
                        'checking'          => 'Dicek',
                        'waiting_sparepart' => 'Menunggu Sparepart',
                        'processing'        => 'Dikerjakan',
                        'finished'          => 'Selesai',
                        'cancelled'         => 'Dibatalkan',
                    ]),
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
            // 'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
