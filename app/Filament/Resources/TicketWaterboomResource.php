<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketWaterboomResource\Pages;
use App\Filament\Resources\TicketWaterboomResource\RelationManagers;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TicketWaterboomResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['Manajer', 'Admin']);
    }

    protected static ?string $model = Ticket::class;

    protected static ?string $pluralModelLabel = 'Tiket';

    protected static ?string $modelLabel = 'Tiket';

    protected static ?string $navigationLabel = 'Tiket';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $activeNavigationIcon = 'heroicon-m-ticket';

    protected static ?string $navigationGroup = 'Water Boom';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Nama')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('qty')
                    ->label('Jumlah')
                    ->default('1')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->default(now()->toDateString())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaksi.orderId')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->badge()
                    ->color('success'),
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
                Tables\Columns\TextColumn::make('')
                    ->label('Total Harga')
                    ->money('Rp. ')
                    ->state(function (Ticket $record): float{
                        $date = Carbon::parse($record['date']);
                        if ($date->isWeekend()) {
                            $price = 35000;
                        } else {
                            $price = 33000;
                        }
                        return (int)$record->qty * (int)$price;
                    }),
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
                                fn (Builder $query, $month): Builder => $query->whereMonth('date', $month),
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
                                fn (Builder $query, $year): Builder => $query->whereYear('date', $year),
                            );

                        Session::put('tahun', $data['tahun']);

                        return $query;
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function($record) {
                        Storage::disk('public')?->delete($record->transaksi->qrcode);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ListTicketWaterbooms::route('/'),
            // 'verifikasi' => Pages\VerifikasiTicketWaterboom::route('/verifikasi'),
            // 'create' => Pages\CreateTicketWaterboom::route('/create'),
            // 'edit' => Pages\EditTicketWaterboom::route('/{record}/edit'),
        ];
    }
}
