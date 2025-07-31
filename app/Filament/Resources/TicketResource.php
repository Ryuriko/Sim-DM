<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $pluralModelLabel = 'Tiket';

    protected static ?string $modelLabel = 'Tiket';

    protected static ?string $navigationLabel = 'Tiket';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $activeNavigationIcon = 'heroicon-m-ticket';

    protected static ?string $navigationGroup = 'Water Boom';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('qty')
                    ->label('Jumlah')
                    ->default('1')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now()->toDateString())
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::where('user_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('transaksi.orderId')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Jumlah Tiket')
                    ->badge()
                    ->color('success'),
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
            'index' => Pages\ListTickets::route('/'),
            // 'view' => Pages\ViewTicket::route('/{record}'),
            // 'create' => Pages\CreateTicket::route('/create'),
            // 'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
