<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Helpers\Helper;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\HistoryPembelianDetail;
use Filament\Forms\Contracts\HasForms;
use App\Models\HistoryPenggunaanDetail;
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
use Filament\Forms\Components\Textarea;

use function Laravel\Prompts\textarea;

class HistoryPembelian extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public $data = [
        'kode' => '',
        'tgl' => '',
        'harga_total' => '',
        'ket' => '',
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
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('tgl')
                    ->searchable()
                    ->label('Tanggal')
                    ->date(),
                TextColumn::make('harga_total')
                    ->label('Harga Total')
                    ->money('Rp.'),
                TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        'pending' => 'warning',
                    }),
                TextColumn::make('ket')
                    ->label('Keterangan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('detail')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn ($record): string => route('filament.admin.resources.histories.pembelian.detail', ['record' => $record])),
                EditAction::make()
                    ->form([
                        Grid::make()
                        ->schema([
                            DateTimePicker::make('tgl')
                                ->label('Tanggal')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'selesai' => 'Selesai'
                                ])
                                ->required(),
                            Textarea::make('ket')
                                ->label('Keteragan')
                        ])
                    ]),
                DeleteAction::make()
                    ->before(function ($record) {
                        try {
                            $details = HistoryPembelianDetail::where('pembelian_id', $record->id)->get();
                            
                            foreach ($details as $detail) {
                                $result = Helper::updateStok($record->id, $detail, 'sub');
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
                    ->label('New Pembelian')
                    ->form([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                // TextInput::make('kode')
                                //     ->unique(ignoreRecord: true)
                                //     ->required(),
                                DateTimePicker::make('tgl')
                                    ->label('Tanggal')
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'selesai' => 'Selesai'
                                    ])
                                    ->required(),
                                Textarea::make('ket')
                                    ->label('Keterangan'),
                        ])
                    ])
                    ->after(function ($record) {
                        $record->update(['kode' => 'PBL00' . $record->id]);
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
