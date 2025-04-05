<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\KategoriBarang as KategoriBarangModel;

class KategoriBarang extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public $data = [
        'nama' => '',
        'ket' => '',
    ];

    public function mount()
    {
        $this->form->fill($this->data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(KategoriBarangModel::query())
            ->columns([
                TextColumn::make('nama'),
                TextColumn::make('ket')
                    ->label('Keterangan'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('nama')
                            ->required(),
                        TextInput::make('ket')
                            ->label('Keterangan')
                    ]),
                DeleteAction::make()
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Kategori')
                    ->form([
                        Grid::make()
                            ->schema([
                                TextInput::make('nama')
                                    ->required(),
                                TextInput::make('ket')
                                    ->label('Keterangan'),
                            ])
                    ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public function render()
    {
        return view('livewire.kategori-barang');
    }
}
