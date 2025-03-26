<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\GymMember;
use Filament\Tables\Table;
use App\Models\GymAttendance;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Session;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GymAttendanceResource\Pages;
use App\Filament\Resources\GymAttendanceResource\RelationManagers;

class GymAttendanceResource extends Resource
{
    protected static ?string $model = GymAttendance::class;

    protected static ?string $pluralModelLabel = 'Kehadiran';

    protected static ?string $modelLabel = 'Kehadiran';

    protected static ?string $navigationLabel = 'Kehadiran';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationGroup = 'Gym';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('member_id')
                    ->options(GymMember::query()->pluck('nama', 'id'))
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TimePicker::make('waktu_masuk')
                    ->required(),
                Forms\Components\TimePicker::make('waktu_keluar')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.nama')
                    ->label('Anggota'),
                Tables\Columns\TextColumn::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->time(),
                Tables\Columns\TextColumn::make('waktu_keluar')
                    ->label('Waktu Masuk')
                    ->time(),
            ])
            ->filters([
                Filter::make('tgl')
                    ->form([
                        DatePicker::make('tgl')
                            ->default(now())
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['tgl'],
                                fn (Builder $query, $date): Builder => $query->where('tgl', $date),
                            );

                        Session::put('filteredDate', $data['tgl']);
                        return $query;
                    })
            ], layout: FiltersLayout::AboveContent)
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
            'index' => Pages\ListGymAttendances::route('/'),
            // 'create' => Pages\CreateGymAttendance::route('/create'),
            // 'edit' => Pages\EditGymAttendance::route('/{record}/edit'),
        ];
    }
}
