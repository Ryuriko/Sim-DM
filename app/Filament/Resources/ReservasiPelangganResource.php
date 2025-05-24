<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasiPelangganResource\Pages;
use App\Filament\Resources\ReservasiPelangganResource\RelationManagers;
use App\Models\Kamar;
use App\Models\Reservasi;
use App\Models\ReservasiKamar;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ReservasiPelangganResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('User');
    }
    
    protected static ?string $model = Reservasi::class;

    protected static ?string $pluralModelLabel = 'Reservasi';

    protected static ?string $modelLabel = 'Reservasi';

    protected static ?string $navigationLabel = 'Reservasi';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-building-office-2';

    protected static ?string $navigationGroup = 'Hotel';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('checkin')
                    ->default(now())
                    ->required(),
                DatePicker::make('checkout')
                    ->default(now()->addDay(1))
                    ->required(),
                Select::make('kamar_id')
                    ->options(function(Get $get) {
                        $checkin = $get('checkin');
                        $checkout = $get('checkout');
                        $reservasiDate = ReservasiKamar::whereBetween('date', [$checkin, $checkout])->pluck('kamar_id');

                        $kamars = Kamar::whereNotIn('id', $reservasiDate)->get()->mapWithKeys(function ($record) {
                            $harga = number_format($record->tipe->harga , 0, ',', '.');
                            $label = $record->tipe->nama . ' - Kamar ' . $record->no . ' - Rp. ' . $harga;

                            return [$record->id => $label];
                        });

                        
                        return $kamars;
                    })
                    ->multiple()
                    ->preload()
                    ->searchable()
                     ->loadingMessage('Mencari kamar yang tersedia...')
                    ->required(),
                TextInput::make('person_qty')
                    ->label('Jumlah Orang')
                    ->numeric()
                    ->required(),
                Textarea::make('ket')
                    ->columnSpanFull()
                    ->label('Keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Reservasi::where('user_id', auth()->user()->id)
            )
            ->columns([
               TextColumn::make('transaksi.orderId')
                    ->label('Order ID'),
                TextColumn::make('user.name')
                    ->label('Nama'),
                TextColumn::make('person_qty')
                    ->label('Jumlah Orang'),
                TextColumn::make('transaksi.order_date')
                    ->date()
                    ->label('Tanggal Pemesanan'),
                TextColumn::make('checkin')
                    ->date(),
                TextColumn::make('checkout')
                    ->date(),
                TextColumn::make('kamars.no')
                    ->label('Kamar')
                    ->listWithLineBreaks(),
                TextColumn::make('transaksi.status')
                    ->badge()
                    ->color(fn (string $state): string => match($state){
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'ots' => 'gray',
                    }),
                TextColumn::make('ket')
                    ->label('Keterangan'),
            ])
            ->filters([
                Filter::make('bln')
                    ->form([
                        Select::make('bulan')
                            ->options([
                                '1' => 'Januari',
                                '2' => 'Februari',
                                '3' => 'Maret',
                                '4' => 'April',
                                '5' => 'Mei',
                                '6' => 'Juni',
                                '7' => 'Juli',
                                '8' => 'Agustus',
                                '9' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->default(now()->month),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['bulan'],
                                fn (Builder $query, $month): Builder => $query->whereHas('transaksi', function ($query) use($month) {
                                    $query->whereMonth('order_date', $month);
                                })
                            );

                        Session::put('bulan', $data['bulan']);

                        return $query;
                    }),
                Filter::make('thn')
                    ->form([
                         Select::make('tahun')
                            ->options([
                                '2024' => '2024',
                                '2025' => '2025',
                            ])
                            ->default(now()->year) 
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['tahun'],
                                fn (Builder $query, $year): Builder => $query->whereHas('transaksi', function ($query) use($year) {
                                    $query->whereYear('order_date', $year);
                                })
                            );

                        Session::put('tahun', $data['tahun']);

                        return $query;
                    }),
            ], layout: FiltersLayout::AboveContent)
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
                    ->before(function ($record) {
                        $record->kamars()->detach();
                    })
                    ->after(function($record) {
                        Storage::disk('public')?->delete($record->transaksi->qrcode);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($record) {
                            $record->kamars()->detach();
                        })
                        ->after(function($record) {
                            Storage::disk('public')?->delete($record->transaksi->qrcode);
                        }),
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
            'index' => Pages\ListReservasiPelanggans::route('/'),
            // 'create' => Pages\CreateReservasiPelanggan::route('/create'),
            // 'edit' => Pages\EditReservasiPelanggan::route('/{record}/edit'),
        ];
    }
}
