<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CompanyProfile;
use Filament\Resources\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyProfileResource\Pages;
use App\Filament\Resources\CompanyProfileResource\RelationManagers;

class CompanyProfileResource extends Resource
{
    protected static ?string $model = CompanyProfile::class;

    protected static ?string $pluralModelLabel = 'Company Profile';

    protected static ?string $modelLabel = 'Company Profile';

    protected static ?string $navigationLabel = 'Company Profile';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-squares-2x2';

    protected static ?string $navigationGroup = 'Web';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(),
                Forms\Components\TextInput::make('tagline'),
                Forms\Components\TextInput::make('alamat'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('telp')
                    ->label('Telepon'),
                Forms\Components\TextInput::make('website'),
                Forms\Components\FileUpload::make('logo')
                    ->directory('company-profile'),
                Forms\Components\TextInput::make('facebook'),
                Forms\Components\TextInput::make('instagram'),
                Forms\Components\TextInput::make('tiktok'),
                Forms\Components\TextInput::make('linkedin'),
                Forms\Components\TextInput::make('whatsapp1'),
                Forms\Components\TextInput::make('whatsapp2'),
                Forms\Components\TextInput::make('whatsapp3'),
                Forms\Components\TextInput::make('ket'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('tagline'),
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('telp'),
                Tables\Columns\TextColumn::make('website'),
                Tables\Columns\TextColumn::make('logo'),
                Tables\Columns\TextColumn::make('facebook'),
                Tables\Columns\TextColumn::make('instagram'),
                Tables\Columns\TextColumn::make('tiktok'),
                Tables\Columns\TextColumn::make('linkedin'),
                Tables\Columns\TextColumn::make('whatsapp1'),
                Tables\Columns\TextColumn::make('whatsapp2'),
                Tables\Columns\TextColumn::make('whatsapp3'),
                Tables\Columns\TextColumn::make('ket'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCompanyProfiles::route('/'),
            // 'view' => Pages\ViewCompanyProfile::route('/{record}'),
            // 'create' => Pages\CreateCompanyProfile::route('/create'),
            // 'index' => Pages\EditCompanyProfile::route('/'),
        ];
    }
}
