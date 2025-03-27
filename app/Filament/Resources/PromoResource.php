<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Filament\Resources\PromoResource\RelationManagers;
use App\Models\Promo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $pluralModelLabel = 'Promo';

    protected static ?string $modelLabel = 'Promo';

    protected static ?string $navigationLabel = 'Promo';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-squares-2x2';

    protected static ?string $navigationGroup = 'Web';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\DateTimePicker::make('tgl_mulai')
                    ->required(),
                Forms\Components\DateTimePicker::make('tgl_selesai')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Mulai')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->label('Selesai')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromos::route('/'),
            // 'create' => Pages\CreatePromo::route('/create'),
            // 'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
