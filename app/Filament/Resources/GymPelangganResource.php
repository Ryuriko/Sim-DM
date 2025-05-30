<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymPelangganResource\Pages;
use App\Filament\Resources\GymPelangganResource\RelationManagers;
use App\Models\GymPelanggan;
use App\Models\GymSubscription;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class GymPelangganResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('User');
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
                DatePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->default(now())
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('tgl_mulai')
                    ->date(),
                TextColumn::make('tgl_selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('qrcode')
                    ->hidden(fn ($record) => $record->status == 'tidak aktif')
                    ->label('QR')
                    ->color('gray')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('QR Code')
                    ->modalSubmitAction(false)
                    ->modalContent(fn ($record) => view('custom.qr-modal', [
                        'qrUrl' => Storage::url($record->transaksi->qrcode),
                    ])),
                Tables\Actions\Action::make('bayar')
                    ->icon('heroicon-o-banknotes')
                    ->label('Pembayaran')
                    ->color('success')
                    ->url(fn ($record) => $record->transaksi->paymentUrl)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->status == 'aktif')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['tgl_selesai'] = Carbon::parse($data['tgl_mulai'])->copy()->addMonth();

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn($record) => $record->status == 'aktif'),
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
            'index' => Pages\ListGymPelanggans::route('/'),
            // 'create' => Pages\CreateGymPelanggan::route('/create'),
            // 'edit' => Pages\EditGymPelanggan::route('/{record}/edit'),
        ];
    }
}
