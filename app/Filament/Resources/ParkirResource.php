<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Parkir;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ParkirResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParkirResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Storage;

class ParkirResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Manajer');
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
                Forms\Components\Select::make('user_id')
                    ->label('Nama')
                    ->relationship('user', 'name')
                    ->required(),
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
                Tables\Actions\EditAction::make()
                    ->after(function($record){
                        $transaction = Transaksi::find($record->transaksi_id);
                        $transaction->update([
                            'paid_at' => $record->date,
                            'used_at' => $record->date,
                        ]);
                    }),
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
            'index' => Pages\ListParkirs::route('/'),
            // 'create' => Pages\CreateParkir::route('/create'),
            // 'edit' => Pages\EditParkir::route('/{record}/edit'),
        ];
    }
}
