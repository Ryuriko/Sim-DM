<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\GymClass;
use Filament\Forms\Form;
use App\Models\GymTrainer;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GymClassResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GymClassResource\RelationManagers;

class GymClassResource extends Resource
{
    protected static ?string $model = GymClass::class;

    protected static ?string $pluralModelLabel = 'Kelas';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pelatih')
                    ->label('Pelatih')
                    ->multiple()
                    ->relationship('pelatihs')
                    ->hint('Bisa lebih dari satu')
                    ->options(GymTrainer::query()->pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\DateTimePicker::make('jadwal')
                    ->required(),
                Forms\Components\TextInput::make('maks')
                    ->label('Kapasitas Maksimal')
                    ->required(),
                Forms\Components\TextInput::make('ket')
                    ->label('Keterangan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelatihs.nama')
                    ->label('Pelatih')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->bulleted(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jadwal')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('maks')
                    ->label('Kapasitas Maksimal'),
                Tables\Columns\TextColumn::make('ket')
                    ->label('Keterangan'),
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
            'index' => Pages\ListGymClasses::route('/'),
            // 'create' => Pages\CreateGymClass::route('/create'),
            // 'edit' => Pages\EditGymClass::route('/{record}/edit'),
        ];
    }
}
