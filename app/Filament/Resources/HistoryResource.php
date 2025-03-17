<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use App\Models\History;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\HistoryPenggunaan;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HistoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HistoryResource\RelationManagers;

class HistoryResource extends Resource
{
    protected static ?string $model = HistoryPenggunaan::class;

    protected static ?string $pluralModelLabel = 'History';

    protected static ?string $modelLabel = 'History';

    protected static ?string $navigationLabel = 'History';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $activeNavigationIcon = 'heroicon-m-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required(),
                Forms\Components\Select::make('barang_id')
                    ->options(Barang::pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->required(),
                Forms\Components\DateTimePicker::make('tgl')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\TextArea::make('ket')
                    ->label('Keterangan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode'),
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang'),
                Tables\Columns\TextColumn::make('Jumlah'),
                Tables\Columns\TextColumn::make('tgl')
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('ket')
                    ->label('Keterangan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Penggunaan')
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
            'index' => Pages\ListHistories::route('/'),
            // 'create' => Pages\CreateHistory::route('/create'),
            // 'edit' => Pages\EditHistory::route('/{record}/edit'),
        ];
    }
}
