<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymMemberResource\Pages;
use App\Filament\Resources\GymMemberResource\RelationManagers;
use App\Models\GymMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GymMemberResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = GymMember::class;

    protected static ?string $pluralModelLabel = 'Member';

    protected static ?string $modelLabel = 'Member';

    protected static ?string $navigationLabel = 'Member';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 1;

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
                    ->numeric()
                    ->minLength(11)
                    ->maxLength(13)
                    ->required(),
                Forms\Components\TextInput::make('alamat'),
                Forms\Components\DatePicker::make('tgl_lahir')
                    ->label('Tanggal Lahir'),
                Forms\Components\DatePicker::make('tgl_gabung')
                    ->label('Tanggal Gabung'),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                        'kadaluwarsa' => 'Kadaluwarsa',
                    ])
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
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_gabung')
                    ->label('Tanggal Gabung')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'kadaluwarsa' => 'gray',
                        'tidak aktif' => 'danger',
                    })
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
            'index' => Pages\ListGymMembers::route('/'),
            // 'create' => Pages\CreateGymMember::route('/create'),
            // 'edit' => Pages\EditGymMember::route('/{record}/edit'),
        ];
    }
}
