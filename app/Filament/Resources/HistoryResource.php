<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\History;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\HistoryPenggunaan;
use Filament\Tables\Actions\Action;
use App\Models\HistoryPenggunaanDetail;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HistoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HistoryResource\RelationManagers;

class HistoryResource extends Resource
{
    protected static ?string $model = HistoryPenggunaan::class;

    protected static ?string $pluralModelLabel = 'Transaksi';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $activeNavigationIcon = 'heroicon-m-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->unique(ignoreRecord: true)
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
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl')
                    ->searchable()
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('total_barang')
                    ->label('Total Barang'),
                Tables\Columns\TextColumn::make('ket')
                    ->searchable()
                    ->label('Keterangan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('detail')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn ($record): string => route('filament.admin.resources.histories.penggunaan.detail', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        try {
                            $details = HistoryPenggunaanDetail::where('penggunaan_id', $record->id)->get();
                            foreach ($details as $detail) {
                                $result = Helper::updateStok($record->id, $detail, 'add', 'penggunaan');
                                $detail->delete();
                            }
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Failed')
                                ->body($th->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
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
            'pembelian.detail' => Pages\HistoryPembelianDetails::route('/{record}/pembelian'),
            'penggunaan.detail' => Pages\HistoryPenggunaanDetails::route('/{record}/penggunaan'),
            // 'create' => Pages\CreateHistory::route('/create'),
            // 'edit' => Pages\EditHistory::route('/{record}/edit'),
        ];
    }
}
