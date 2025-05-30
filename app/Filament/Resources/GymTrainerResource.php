<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\GymPaket;
use Filament\Forms\Form;
use App\Models\GymMember;
use App\Models\GymTrainer;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GymTrainerResource\Pages;
use App\Filament\Resources\GymTrainerResource\RelationManagers;

class GymTrainerResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = GymTrainer::class;

    protected static ?string $pluralModelLabel = 'Pelatih';

    protected static ?string $modelLabel = 'Pelatih';

    protected static ?string $navigationLabel = 'Pelatih';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('telp')
                    ->label('Telepon')
                    ->minLength(11)
                    ->maxLength(13)
                    ->required(),
                Forms\Components\TextInput::make('spesialisasi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('telp')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('spesialisasi'),
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
            'index' => Pages\ListGymTrainers::route('/'),
            // 'create' => Pages\CreateGymTrainer::route('/create'),
            // 'edit' => Pages\EditGymTrainer::route('/{record}/edit'),
        ];
    }
}
