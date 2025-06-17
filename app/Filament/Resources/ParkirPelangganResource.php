<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Parkir;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParkirPelangganResource\Pages;
use Illuminate\Support\Collection;
use App\Filament\Resources\ParkirPelangganResource\RelationManagers;

class ParkirPelangganResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['User']);
    }

    protected static ?string $model = Parkir::class;

    protected static ?string $pluralModelLabel = 'Parkir';

    protected static ?string $modelLabel = 'Parkir';

    protected static ?string $navigationLabel = 'Parkir';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $activeNavigationIcon = 'heroicon-m-truck';

    protected static ?string $navigationGroup = 'Parkir';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now()->toDateString())
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Parkir::where('user_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('transaksi.orderId')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('transaksi.status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'ots' => 'gray'
                    }),
                Tables\Columns\TextColumn::make('transaksi.paid_at')
                    ->label('Dibayar')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('transaksi.used_at')
                    ->label('Digunakan')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                 Tables\Actions\Action::make('qrcode')
                    ->hidden(fn ($record) => $record->transaksi->status == 'unpaid')
                    ->label('QR')
                    ->color('gray')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code')
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('custom.qr-modal', [
                        'qrUrl' => Storage::url($record->transaksi->qrcode),
                    ])),
                Tables\Actions\Action::make('bayar')
                    ->hidden(fn ($record) => $record->transaksi->status == 'ots')
                    ->icon('heroicon-o-banknotes')
                    ->label('Pembayaran')
                    ->color('success')
                    ->url(fn ($record) => $record->transaksi->paymentUrl)
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->after(function($record) {
                        Storage::disk('public')?->delete($record->transaksi->qrcode);
                    }),
            ])
           ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                Storage::disk('public')?->delete($record->transaksi->qrcode);
                            }

                            $record->delete();
                        })
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
            'index' => Pages\ListParkirPelanggans::route('/'),
            // 'create' => Pages\CreateParkirPelanggan::route('/create'),
            // 'edit' => Pages\EditParkirPelanggan::route('/{record}/edit'),
        ];
    }
}
