<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\TicketResource\Pages;
use App\Filament\User\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
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
            ->columns([
                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger'
                    }),
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
            'index' => Pages\ListTickets::route('/'),
            'view' => Pages\ViewTicket::route('/{record}'),
            // 'create' => Pages\CreateTicket::route('/create'),
            // 'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
