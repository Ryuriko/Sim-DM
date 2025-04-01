<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Tipe;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\TipeKamar;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TipeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TipeResource\RelationManagers;

class TipeResource extends Resource
{
    protected static ?string $model = TipeKamar::class;

    protected static ?string $pluralModelLabel = 'Tipe';

    protected static ?string $modelLabel = 'Tipe';

    protected static ?string $navigationLabel = 'Tipe';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-building-office-2';

    // protected static ?string $navigationParentItem = 'Kamar';

    protected static ?string $navigationGroup = 'Hotel';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\TextInput::make('kapasitas')
                    ->required(),
                Forms\Components\TextInput::make('harga')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kapasitas'),
                Tables\Columns\TextColumn::make('harga')
                    ->money('Rp.')
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
            'index' => Pages\ListTipes::route('/'),
            // 'create' => Pages\CreateTipe::route('/create'),
            // 'edit' => Pages\EditTipe::route('/{record}/edit'),
        ];
    }
}
