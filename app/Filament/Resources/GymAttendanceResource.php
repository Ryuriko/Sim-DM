<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymAttendanceResource\Pages;
use App\Filament\Resources\GymAttendanceResource\RelationManagers;
use App\Models\GymAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GymAttendanceResource extends Resource
{
    protected static ?string $model = GymAttendance::class;

    protected static ?string $pluralModelLabel = 'Kehadiran';

    protected static ?string $modelLabel = 'Kehadiran';

    protected static ?string $navigationLabel = 'Kehadiran';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListGymAttendances::route('/'),
            'create' => Pages\CreateGymAttendance::route('/create'),
            'edit' => Pages\EditGymAttendance::route('/{record}/edit'),
        ];
    }
}
