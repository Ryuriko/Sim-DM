<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $pluralModelLabel = 'Karyawan';

    protected static ?string $modelLabel = 'Karyawan';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Select::make('role_id')
                    ->options(Role::whereNot('name', 'sistem')->pluck('name', 'id'))
                    ->label('Role')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->length(16)
                    ->unique(ignoreRecord: true)
                    ->required(),
                DatePicker::make('date_of_entry')
                    ->label('Tanggal Masuk')
                    ->required(),
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'keluar' => 'Keluar',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereDoesntHave('role', function($query) {
                    $query->where('name', 'sistem');
                })
                ->with('role')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama'),
                    
                TextColumn::make('role.name')
                    ->label('Role'),
                TextColumn::make('email'),
                TextColumn::make('nik')
                    ->label('NIK'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'cuti' => 'warning',
                        'keluar' => 'danger',
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
