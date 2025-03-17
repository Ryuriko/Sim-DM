<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $pluralModelLabel = 'Barang';

    protected static ?string $modelLabel = 'Barang';

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $activeNavigationIcon = 'heroicon-m-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    // ->default()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->label('Kategori')
                    ->options(KategoriBarang::pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('merk')
                    ->required(),
                Forms\Components\TextInput::make('stok')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('satuan')
                    ->options([
                        'pcs' => 'pcs',
                        'box' => 'box',
                        'liter' => 'liter',
                        'kg' => 'kg',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('harga_beli')
                    ->label('Harga Beli')
                    ->required(),
                Forms\Components\TextInput::make('harga_jual')
                    ->label('Harga Jual'),
                Forms\Components\Select::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('nama', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi Barang'),
                Forms\Components\Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ])
                    ->required(),
                Forms\Components\TextArea::make('ket')
                    ->label('Keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode'),
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('kategori.nama'),
                Tables\Columns\TextColumn::make('merk'),
                Tables\Columns\TextColumn::make('stok')
                    ->badge()
                    ->color('gray')
                    ->numeric(),
                Tables\Columns\TextColumn::make('satuan')
                    ->numeric(),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('Rp.'),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->money('Rp.')
                    ->numeric(),
                Tables\Columns\TextColumn::make('supplier.nama'),
                Tables\Columns\TextColumn::make('lokasi'),
                Tables\Columns\TextColumn::make('status')                
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('ket')
                    ->words(5),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
