<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CutiKaryawanResource\Pages;
use App\Filament\Resources\CutiKaryawanResource\RelationManagers;
use App\Models\Cuti;
use App\Models\CutiKaryawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CutiKaryawanResource extends Resource
{
    protected static ?string $model = Cuti::class;
    
    protected static ?string $pluralModelLabel = 'Cuti';

    protected static ?string $modelLabel = 'Cuti';

    protected static ?string $navigationLabel = 'Cuti';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Karyawan Menu';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),
                Forms\Components\Textarea::make('alasan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                CutiKaryawan::where('user_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->searchable()
                    ->label('Tanggal Mulai'),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->searchable()
                    ->label('Tanggal Selesai'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->status == 'disetujui'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn($record) => $record->status == 'disetujui'),
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
            'index' => Pages\ListCutiKaryawans::route('/'),
            // 'create' => Pages\CreateCutiKaryawan::route('/create'),
            // 'edit' => Pages\EditCutiKaryawan::route('/{record}/edit'),
        ];
    }
}
