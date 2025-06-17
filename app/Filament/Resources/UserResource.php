<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Manajer');
    }

    protected static ?string $model = User::class;

    protected static ?string $pluralModelLabel = 'Karyawan';

    protected static ?string $modelLabel = 'Karyawan';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Karyawan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
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
                TextInput::make('rek_vendor')
                    ->label('Vendor Rekening')
                    ->hint('BRI, Mandiri, dan lainnya'),
                TextInput::make('rek_no')
                    ->label('Nomor Rekening'),
                TextInput::make('rek_nama')
                    ->label('Rekening atas nama'),
                Select::make('tipe')
                    ->options([
                        'tetap' => 'Tetap',
                        'kontrak' => 'Kontrak',
                        'vendor' => 'Vendor',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'keluar' => 'Keluar',
                    ])
                    ->required(),
                FileUpload::make('ktp')
                    ->label('KTP')
                    ->directory('user')
                    ->image()
                    ->imageEditor(),
                FileUpload::make('foto')
                    ->label('Foto')
                    ->directory('user')
                    ->image()
                    ->imageEditor(),
                FileUpload::make('cv')
                    ->label('CV')
                    ->directory('user'),
                FileUpload::make('sk')
                    ->label('SK')
                    ->directory('user'),
                FileUpload::make('bpjs')
                    ->label('BPJS')
                    ->directory('user')
                    ->image()
                    ->imageEditor(),
                FileUpload::make('surat_kontrak')
                    ->label('Surat Kontrak')
                    ->directory('user'),
                FileUpload::make('sertifikat')
                    ->label('Sertifikat')
                    ->hint('Keahlian, penghargaan dan lainnya')
                    ->directory('user')
                    ->image()
                    ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'super_admin')
                        ->orWhere('name', 'User');
                })
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                TextColumn::make('roles.name')
                    ->badge()
                    ->color('success'),
                TextColumn::make('email'),
                TextColumn::make('nik')
                    ->label('NIK'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
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
