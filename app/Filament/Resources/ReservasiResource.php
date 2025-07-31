<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasiResource\Pages;
use App\Filament\Resources\ReservasiResource\RelationManagers;
use App\Models\Kamar;
use App\Models\ManajemenReservasi;
use App\Models\Reservasi;
use App\Models\ReservasiKamar;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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

class ReservasiResource extends Resource
{  
    protected static ?string $model = ManajemenReservasi::class;

    protected static ?string $pluralModelLabel = 'Manajemen Reservasi';

    protected static ?string $modelLabel = 'Manajemen Reservasi';

    protected static ?string $navigationLabel = 'Manajemen Reservasi';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-building-office-2';

    protected static ?string $navigationGroup = 'Hotel';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->columnSpanFull()
                    ->relationship('user', 'name')
                    ->preload()
                    ->required(),
                DatePicker::make('checkin')
                    ->default(now())
                    ->required(),
                DatePicker::make('checkout')
                    ->default(now()->addDay(1))
                    ->required(),
                Select::make('kamar_id')
                    // ->relationship(
                    //     name: 'kamar',
                    //     titleAttribute: 'no', 
                    //     modifyQueryUsing: function (Builder $query, Get $get) {
                    //         $checkin = $get('checkin');
                    //         $checkout = $get('checkout');
                    //         $reservasiDate = ReservasiDate::whereBetween('date', [$checkin, $checkout])->pluck('kamar_id');

                    //         return $query->whereNotIn('id', $reservasiDate);
                    //     }
                    // )
                    // ->getOptionLabelFromRecordUsing(function($record) {
                    //     $harga = number_format($record->tipe->harga , 0, ',', '.');

                    //     return $record->tipe->nama . ' - Kamar ' . $record->no . ' - Rp. ' . $harga;
                    // })
                    ->options(function(Get $get) {
                        $checkin = Carbon::parse($get('checkin'))->toDateString();
                        $checkout = Carbon::parse($get('checkout'))->subDay()->toDateString();

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
                // TextColumn::make('kamars.no')
                //     ->label('Kamar')
                //     ->getStateUsing(function ($record) {
                //         return $record->kamars
                //             ->unique('no') // ✅ menghindari duplikat berdasarkan field 'no'
                //             ->map(fn($kamar) => $kamar->no);
                //             // ->implode("\n");
                //     })
                //     ->listWithLineBreaks(),
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
            'index' => Pages\ListReservasis::route('/'),
            // 'create' => Pages\CreateReservasi::route('/create'),
            // 'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }
}
