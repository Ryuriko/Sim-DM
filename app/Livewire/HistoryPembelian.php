<?php

namespace App\Livewire;

use App\Models\Barang;
use Livewire\Component;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\HistoryPembelian as HistoryPembelianModel;

class HistoryPembelian extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    
    public $data = [
        'kode' => '',
        'supplier_id' => '',
        'barang_id' => '',
        'jumlah' => '',
        'harga_satuan' => '',
        'tgl' => '',
        'harga_total' => '',
        'status' => '',
    ];

    public function mount()
    {
        $this->form->fill($this->data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(HistoryPembelianModel::query())
            ->columns([
                TextColumn::make('kode'),
                TextColumn::make('supplier.nama')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->label('Supplier'),
                TextColumn::make('barang.nama')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->label('Barang'),
                TextColumn::make('jumlah'),
                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan'),
                TextColumn::make('tgl')
                    ->label('Tanggal'),
                TextColumn::make('harga_total')
                    ->label('Harga Total'),
                TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'pending' => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('kode')
                            ->required(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::pluck('nama', 'id'))
                            ->required(),
                        Select::make('barang_id')
                            ->label('Barang')
                            ->options(Barang::pluck('nama', 'id'))
                            ->required(),
                        TextInput::make('barang.satuan')
                            ->label('Satuan Barang')
                            ->readOnly(),
                        TextInput::make('jumlah')
                            ->required(),
                        TextInput::make('harga_satuan')
                            ->numeric()
                            ->required(),
                        DateTimePicker::make('tgl')
                            ->label('Tanggal')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'selesai' => 'Selesai'
                            ])
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['harga_total'] = $data['harga_satuan'] * $data['jumlah'];
                 
                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Pembelian')
                    ->form([
                        TextInput::make('kode')
                            ->required(),
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::pluck('nama', 'id'))
                            ->required(),
                        Select::make('barang_id')
                            ->label('Barang')
                            ->options(Barang::pluck('nama', 'id'))
                            ->required(),
                        TextInput::make('barang.satuan')
                            ->label('Satuan Barang')
                            ->readOnly(),
                        TextInput::make('jumlah')
                            ->required(),
                        TextInput::make('harga_satuan')
                            ->numeric()
                            ->required(),
                        DateTimePicker::make('tgl')
                            ->label('Tanggal')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'selesai' => 'Selesai'
                            ])
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['harga_total'] = $data['harga_satuan'] * $data['jumlah'];
                 
                        return $data;
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public function render()
    {
        return view('livewire.history-pembelian');
    }
}
