<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsenKaryawanResource\Pages;
use App\Filament\Resources\AbsenKaryawanResource\RelationManagers;
use App\Models\Absensi;
use App\Models\AbsenUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsenKaryawanResource extends Resource
{
    protected static ?string $model = Absensi::class;
    
    protected static ?string $pluralModelLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Karyawan Menu';

    protected static ?int $navigationSort = 1;

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
            'index' => Pages\ListAbsenKaryawans::route('/'),
            'create' => Pages\CreateAbsenKaryawan::route('/create'),
            'edit' => Pages\EditAbsenKaryawan::route('/{record}/edit'),
        ];
    }
}
