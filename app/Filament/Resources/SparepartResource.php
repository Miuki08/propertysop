<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparepartResource\Pages;
use App\Filament\Resources\SparepartResource\RelationManagers;
use App\Models\Sparepart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SparepartResource extends Resource
{
    protected static ?string $model = Sparepart::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('code')
                //     ->label('Code')
                //     ->maxLength(255),
                Forms\Components\TextInput::make('code')
                        ->label('Code')
                        ->maxLength(255)
                        ->placeholder('Kosongkan untuk generate otomatis'),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->prefix('Rp'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Create Date')
                    ->dateTime('d/m/Y H:i')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update Date')
                    ->dateTime('d/m/Y H:i')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpareparts::route('/'),
            'create' => Pages\CreateSparepart::route('/create'),
            'edit' => Pages\EditSparepart::route('/{record}/edit'),
        ];
    }
}
