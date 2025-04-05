<?php

namespace App\Livewire;


use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\CompanyProfile;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CustomPageCompanyProfile extends Component implements HasForms
{
    use InteractsWithForms;

    public $company;

    public function mount()
    {
        $this->company = CompanyProfile::firstOrNew();
        Session::put('id', $this->company['id']);

        $this->form->fill([
            'nama' => $this->company->nama ?? 'test', // Pakai default jika kosong
            'tagline' => $this->company->tagline,
            'alamat' => $this->company->alamat,
            'email' => $this->company->email,
            'telp' => $this->company->telp,
            'website' => $this->company->website,
            'logo' => $this->company->logo,
            'facebook' => $this->company->facebook,
            'instagram' => $this->company->instagram,
            'tiktok' => $this->company->tiktok,
            'linkedin' => $this->company->linkedin,
            'whatsapp1' => $this->company->whatsapp1,
            'whatsapp2' => $this->company->whatsapp2,
            'whatsapp3' => $this->company->whatsapp3,
            'ket' => $this->company->ket,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->default('test')
                        ->required(),
                    Forms\Components\TextInput::make('tagline'),
                    Forms\Components\TextInput::make('alamat'),
                    Forms\Components\TextInput::make('email'),
                    Forms\Components\TextInput::make('website'),
                    Forms\Components\TextInput::make('facebook'),
                    Forms\Components\TextInput::make('instagram'),
                    Forms\Components\TextInput::make('tiktok'),
                    Forms\Components\TextInput::make('linkedin'),
                    Forms\Components\TextInput::make('whatsapp1'),
                    Forms\Components\TextInput::make('whatsapp2'),
                    Forms\Components\TextInput::make('whatsapp3'),
                    Forms\Components\TextInput::make('ket'),
                    Forms\Components\FileUpload::make('logo')
                        ->directory('company-profile'),
                ])
            ])
            ->statePath('company');
    }

    public function save()
    {
        try {
            $validated = $this->form->getState();

            $id = Session::get('id');
            $data = CompanyProfile::find($id)->update($validated);

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

    public function render()
    {
        return view('livewire.custom-page-company-profile');
    }
}
