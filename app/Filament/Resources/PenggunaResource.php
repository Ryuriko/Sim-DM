<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaResource\Pages;
use App\Filament\Resources\PenggunaResource\RelationManagers;
use App\Models\Pengguna;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenggunaResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }

    protected static ?string $model = User::class;

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('email')
                    ->unique()
                    ->email()
                    ->required(),
                TextInput::make('phone'),
                TextInput::make('password')
                    ->minLength(8),
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'keluar' => 'Blocked',
                    ])
                    ->required(),
                
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereHas('roles', function($query) {
                    $query->where('name', 'User');
                })
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('email'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'keluar' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'aktif' => 'Aktif',
                        'keluar' => 'Blocked'
                    }),
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
            'index' => Pages\ListPenggunas::route('/'),
            // 'create' => Pages\CreatePengguna::route('/create'),
            // 'edit' => Pages\EditPengguna::route('/{record}/edit'),
        ];
    }
}
