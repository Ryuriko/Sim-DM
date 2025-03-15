<?php

namespace App\Livewire;

use App\Models\Role;
use Filament\Forms\Components\Fieldset;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class SalaryRolePenggajian extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [
        'id' => '',
        'manajer' => '',
        'admin' => '',
        'gudang' => '',
        'karyawan' => '',
    ];

    public function mount()
    {
        $salary = Role::whereNot('name', 'sistem')->get();
        $manajer = $salary->firstWhere('name', 'manajer')['salary'];
        $admin = $salary->firstWhere('name', 'admin')['salary'];
        $gudang = $salary->firstWhere('name', 'gudang')['salary'];
        $karyawan = $salary->firstWhere('name', 'karyawan')['salary'];

        $this->form->fill([
            'manajer' => $manajer,
            'admin' => $admin,
            'gudang' => $gudang,
            'karyawan' => $karyawan,
        ]);
    }

    public function save()
    {
        
        try {
            $validated = $this->form->getState();

            foreach ($validated as $key => $val) {
                $opr[$key] = Role::where('name', $key)->first();
                $opr[$key] = $opr[$key]->update(['salary' => $val]);
            }

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
                Fieldset::make('Gaji Pokok')
                ->schema([
                    TextInput::make('manajer')
                        ->required()
                        ->numeric(),
                    TextInput::make('admin')
                        ->required()
                        ->numeric(),
                    TextInput::make('gudang')
                        ->required()
                        ->numeric(),
                    TextInput::make('karyawan')
                        ->required()
                        ->numeric(),
                ])
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.salary-role-penggajian');
    }
}
