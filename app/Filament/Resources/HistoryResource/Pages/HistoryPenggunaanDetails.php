<?php

namespace App\Filament\Resources\HistoryResource\Pages;

use App\Models\Barang;
use App\Helpers\Helper;
use App\Models\Supplier;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use App\Models\HistoryPenggunaanDetail;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use App\Filament\Resources\HistoryResource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class HistoryPenggunaanDetails extends Page implements HasForms, HasTable
{
    protected static string $resource = HistoryResource::class;

    protected static string $view = 'filament.resources.history-resource.pages.history-penggunaan-details';

    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithRecord;
    
    public $data = [
        'pembelian_id' => '',
        'barang_id' => '',
        'jumlah' => '',
    ];

    public function mount(int | string $record)
    {
        $this->record = $this->resolveRecord($record);
        $this->form->fill($this->data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(HistoryPenggunaanDetail::query())
            ->modifyQueryUsing(function ($query, $livewire) {
                return $query->where('penggunaan_id', $livewire->record->id);
            })
            ->columns([
                TextColumn::make('barang.nama')
                    ->label('Barang'),
                TextColumn::make('jumlah')
                    ->numeric(),
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
                                    ->options(Barang::query()->pluck('nama', 'id'))
                                    ->required(),
                                TextInput::make('jumlah')
                                    ->numeric()
                                    ->required(),
                        ])
                    ])
                    ->mutateFormDataUsing(function (EditAction $action, $record, array $data, $livewire): array {
                        $id = $livewire->record->id;
                        $data['jumlah_before'] = $record['jumlah'];
                        $result = Helper::updateStok($id, $data, 'edit', 'penggunaan');
    
                        if($result['status'] != true) {
                            Notification::make()
                                ->title('Failed')
                                ->danger()
                                ->body($result['message']);
    
                            $action->halt();
                        }
    
                        unset($data['jumlah_before']);
                        $data['penggunaan_id'] = $id;
                        
                        return $data;
    
                    }),
                DeleteAction::make()
                    ->before(function ($record, $livewire) {
                        $result = Helper::updateStok($livewire->record->id, $record, 'add', 'penggunaan');
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
                                    ->options(Barang::query()->pluck('nama', 'id'))
                                    ->required(),
                                TextInput::make('jumlah')
                                    ->numeric()
                                    ->required(),
                        ])
                ])
                ->mutateFormDataUsing(function (CreateAction $action, array $data, $livewire): array {
                    $id = $livewire->record->id;
                    $result = Helper::updateStok($id, $data, 'sub', 'penggunaan');

                    if($result['status'] != true) {
                        Notification::make()
                            ->title('Failed')
                            ->danger()
                            ->body($result['message']);

                        $action->halt();
                    }

                    $data['penggunaan_id'] = $id;

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
