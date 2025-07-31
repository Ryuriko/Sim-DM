<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QrVerificationResource\Pages;
use App\Filament\Resources\QrVerificationResource\RelationManagers;
use App\Models\QrVerification;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QrVerificationResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $pluralModelLabel = 'QR Verification';

    protected static ?string $modelLabel = 'QR Verification';

    protected static ?string $navigationLabel = 'QR Verification';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $activeNavigationIcon = 'heroicon-m-qr-code';

    protected static ?string $navigationGroup = 'QR Verification';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\Verification::route('/'),
            // 'create' => Pages\CreateQrVerification::route('/create'),
            // 'edit' => Pages\EditQrVerification::route('/{record}/edit'),
        ];
    }
}
