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
        $roles = Role::whereNot('name', 'super_admin')->get(['name', 'salary']);
        foreach ($roles as $key => $value) {
            $role[$value['name']] = $value['salary'];
        }

        $this->form->fill([
            'manajer' => $role['Manajer'],
            'admin' => $role['Admin'],
            'gudang' => $role['Gudang'],
            'karyawan' => $role['Karyawan'],
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
        $roles = Role::whereNot('name', 'super_admin')->get(['name', 'salary']);
        foreach ($roles as $key => $value) {
            $role[$value['name']] = $value['salary'];
        }

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
