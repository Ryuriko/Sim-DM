<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\GymPaket;
use Filament\Forms\Form;
use App\Models\GymMember;
use Filament\Tables\Table;
use App\Models\GymSubscription;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GymSubscriptionResource\Pages;
use App\Filament\Resources\GymSubscriptionResource\RelationManagers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GymSubscriptionResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Manajer');
    }
    protected static ?string $model = GymSubscription::class;

    protected static ?string $pluralModelLabel = 'Membership';

    protected static ?string $modelLabel = 'Membership';

    protected static ?string $navigationLabel = 'Membership';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('tgl_mulai')
                    ->default(now())
                    ->label('Tanggal Mulai'),   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(GymSubscription::where('status', 'aktif'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Pengguna'),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('transaksi.status')
                    ->label('Status Transaksi')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'ots' => 'gray',
                    }),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\Action::make('qrcode')
                //     ->hidden(fn ($record) => $record->transaksi->status == 'unpaid')
                //     ->label('QR')
                //     ->color('gray')
                //     ->icon('heroicon-o-qr-code')
                //     ->modalHeading('QR Code')
                //     ->modalSubmitAction(false)
                //     ->modalContent(fn ($record) => view('custom.qr-modal', [
                //         'qrUrl' => Storage::url($record->transaksi->qrcode),
                //     ])),
                // Tables\Actions\Action::make('bayar')
                //     ->hidden(fn ($record) => $record->transaksi->status == 'ots')
                //     ->icon('heroicon-o-banknotes')
                //     ->label('Pembayaran')
                //     ->color('success')
                //     ->url(fn ($record) => $record->transaksi->paymentUrl)
                //     ->openUrlInNewTab(),
                // Tables\Actions\EditAction::make()
                //     ->mutateFormDataUsing(function (array $data) {
                //         $data['tgl_selesai'] = Carbon::parse($data['tgl_mulai'])->copy()->addMonth();

                //         return $data;
                //     }),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGymSubscriptions::route('/'),
            // 'create' => Pages\CreateGymSubscription::route('/create'),
            // 'edit' => Pages\EditGymSubscription::route('/{record}/edit'),
        ];
    }
}
