<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CutiResource\Pages;
use App\Filament\Resources\CutiResource\RelationManagers;
use App\Models\Cuti;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CutiResource extends Resource
{
    protected static ?string $model = Cuti::class;

    protected static ?string $pluralModelLabel = 'Cuti';

    protected static ?string $modelLabel = 'Cuti';

    protected static ?string $navigationLabel = 'Cuti';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Karyawan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Karyawan')
                    ->options(User::whereDoesntHave('roles', function($query) {
                        $query->where('name', 'super_admin');
                    })->pluck('name', 'id'))
                    ->required(),
                Forms\Components\DatePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'disetujui' => 'Disetujui',
                        'menunggu' => 'Menunggu',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required(),
                Forms\Components\TextArea::make('alasan')
                    ->label('Alasan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Karyawan'),
                TextColumn::make('user.role.name')
                    ->label('Jabatan')
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state))),
                TextColumn::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->date(),
                TextColumn::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->date(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime(),
                TextColumn::make('status')                
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
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
            'index' => Pages\ListCutis::route('/'),
            // 'create' => Pages\CreateCuti::route('/create'),
            // 'edit' => Pages\EditCuti::route('/{record}/edit'),
        ];
    }
}
