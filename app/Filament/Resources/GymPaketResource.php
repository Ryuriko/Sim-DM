<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymPaketResource\Pages;
use App\Filament\Resources\GymPaketResource\RelationManagers;
use App\Models\GymPaket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GymPaketResource extends Resource
{
    protected static ?string $model = GymPaket::class;

    protected static ?string $pluralModelLabel = 'Paket';

    protected static ?string $modelLabel = 'Paket';

    protected static ?string $navigationLabel = 'Paket';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\TextInput::make('harga')
                    ->required(),
                Forms\Components\TextInput::make('ket')
                    ->label('Keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('harga')
                    ->money('Rp.'),
                Tables\Columns\TextColumn::make('ket')
                    ->label('Keterangan')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGymPakets::route('/'),
            // 'create' => Pages\CreateGymPaket::route('/create'),
            // 'edit' => Pages\EditGymPaket::route('/{record}/edit'),
        ];
    }
}
