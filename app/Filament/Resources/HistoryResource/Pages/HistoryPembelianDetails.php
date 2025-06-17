<?php

namespace App\Filament\Resources\HistoryResource\Pages;

use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\Supplier;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use App\Models\HistoryPembelian;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use App\Models\HistoryPembelianDetail;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use App\Filament\Resources\HistoryResource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class HistoryPembelianDetails extends Page implements HasForms, HasTable
{
    protected static string $resource = HistoryResource::class;

    protected static string $view = 'filament.resources.history-resource.pages.history-pembelian-details';

    use InteractsWithTable;
    use InteractsWithForms;
    
    public $data = [
        'pembelian_id' => '',
        'barang_id' => '',
        'supplier_id' => '',
        'jumlah' => '',
        'harga_satuan' => '',
        'subtotal' => '',
    ];
    
    public static $record;

    public function mount(Request $request)
    {
        $id = $request->route('record');
        self::$record = HistoryPembelian::find($id);
        Session::put('id', $id);
        $this->form->fill($this->data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(HistoryPembelianDetail::query())
            ->modifyQueryUsing(function ($query) {
                $id = Session::get('id');
                return $query->where('pembelian_id', $id);
            })
            ->columns([
                TextColumn::make('barang.nama')
                    ->label('Barang'),
                TextColumn::make('supplier.nama')
                    ->label('Supplier'),
                TextColumn::make('jumlah')
                    ->badge()
                    ->color('success')
                    ->numeric(),
                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('Rp.'),
                TextColumn::make('subtotal')
                    ->label('Sub Total')
                    ->money('Rp.'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Grid::make()
                            ->schema([
                                Select::make('barang_id')
                                    ->label('Barang')
                                    ->live()
                                    ->options(Barang::query()->pluck('nama', 'id'))
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('satuan', Barang::find($state)?->satuan))
                                    ->required(),
                                TextInput::make('satuan')
                                    ->label('Satuan')
                                    ->dehydrated(false)
                                    ->readOnly(),
                                Select::make('supplier_id')
                                    ->options(Supplier::query()->pluck('nama', 'id'))
                                    ->required(),
                                TextInput::make('jumlah')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('harga_satuan')
                                    ->numeric()
                                    ->required(),
                        ])
                    ])
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['satuan'] = Barang::find($data['barang_id'])['satuan'];
                 
                        return $data;
                    })
                    ->mutateFormDataUsing(function (EditAction $action, $record, array $data): array {
                        $id = Session::get('id');
                        $data['jumlah_before'] = $record['jumlah'];
                        $data['subtotal_before'] = $record['subtotal'];
                        $result = Helper::updateStok($id, $data, 'edit');

                        if($result['status'] == false) {
                            Notification::make()
                                ->title('Failed')
                                ->danger()
                                ->body($result['message']);
    
                            $action->halt();
                        }
    
                        unset($data['jumlah_before']);
                        unset($data['subtotal_before']);
                        $data['pembelian_id'] = $id;
                        $data['subtotal'] = $result['subtotal'];
                 
                        return $data;
    
                    }),
                DeleteAction::make()
                    ->before(function ($record) {
                        $id = Session::get('id');
                        $result = Helper::updateStok($id, $record, 'sub');
                        if($result['status'] == false) {
                            Notification::make()
                                ->title('Failed')
                                ->danger()
                                ->body($result['message']);

                                return false;
                        }
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New')
                    ->form([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('barang_id')
                                    ->live()
                                    ->options(Barang::query()->pluck('nama', 'id'))
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('satuan', Barang::find($state)?->satuan))
                                    ->required(),
                                TextInput::make('satuan')
                                    ->label('Satuan')
                                    ->dehydrated(false)
                                    ->readOnly()
                                    ->required(),
                                Select::make('supplier_id')
                                    ->options(Supplier::query()->pluck('nama', 'id'))
                                    ->required(),
                                TextInput::make('jumlah')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('harga_satuan')
                                    ->numeric()
                                    ->required(),
                        ])
                ])
                ->mutateFormDataUsing(function (CreateAction $action, array $data): array {
                    $id = Session::get('id');
                    $result = Helper::updateStok($id, $data, 'add');
                    if($result['status'] == false) {
                        Notification::make()
                            ->title('Failed')
                            ->danger()
                            ->body($result['message']);

                        $action->halt();
                    }

                    $data['pembelian_id'] = $id;
                    $data['subtotal'] = $result['subtotal'];
             
                    return $data;

                }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
