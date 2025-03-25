<?php

namespace App\Filament\Resources;

use Filament\Forms;
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

class GymSubscriptionResource extends Resource
{
    protected static ?string $model = GymSubscription::class;

    protected static ?string $pluralModelLabel = 'Langganan';

    protected static ?string $modelLabel = 'Langganan';

    protected static ?string $navigationLabel = 'Langganan';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('member_id')
                    ->options(GymMember::query()->pluck('nama', 'id'))
                    ->required(),
                Forms\Components\Select::make('paket_id')
                    ->options(GymPaket::query()->pluck('nama', 'id'))
                    ->required(),
                Forms\Components\DatePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai'),
                Forms\Components\DatePicker::make('tgl_selesai')
                    ->label('Tanggal Selesai'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.nama')
                    ->label('Anggota'),
                Tables\Columns\TextColumn::make('paket.nama')
                    ->label('Paket'),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->date(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
