<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\SettingPenggajian as SettingPenggajianModel;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class SettingPenggajian extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [
        'id' => '',
        'potongan_alpha' => '',
        'potongan_cuti' => '',        
    ];

    public function mount()
    {
        $setting = SettingPenggajianModel::first();

        $this->form->fill([
            'id' => $setting['id'],
            'potongan_alpha' => $setting['potongan_alpha'],
            'potongan_cuti' => $setting['potongan_cuti'],
        ]);
    }

    public function save()
    {
        
        try {
            $validated = $this->form->getState();
            
            $opr = SettingPenggajianModel::find($validated['id'])->update($validated);  

            Notification::make()
                ->title('Saved')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed')
                ->danger()
                ->body($th->getMessage())
                ->send();
        }

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Potongan')
                    ->schema([
                        Hidden::make('id'),
                        TextInput::make('potongan_alpha')
                            ->required()
                            ->numeric(),
                        TextInput::make('potongan_cuti')
                            ->required()
                            ->numeric(),
                    ])
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.setting-penggajian');
    }
}
