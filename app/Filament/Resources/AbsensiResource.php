<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Absensi;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AbsensiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $pluralModelLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('jam_masuk')
                    ->required(),
                Forms\Components\TimePicker::make('jam_keluar')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                        'alpha' => 'Alpha',
                    ])
                    ->required(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereDoesntHave('role', function($query) {
                    $query->where('name', 'sistem');
                })
                ->with('absensis', function($query) {
                    $date = Session::get('filteredDate') ?? Carbon::now()->toDateString();
                    $query->where('tgl', $date); 
            }))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('absensis.jam_masuk')
                    ->label('Jam Masuk')
                    ->time(),
                TextColumn::make('absensis.jam_keluar')
                    ->label('Jam Keluar')
                    ->time(),
                TextColumn::make('absensis.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'sakit' => 'warning',
                        'izin' => 'gray',
                        'alpha' => 'danger',
                    })
            ])
            ->filters([
                Filter::make('tgl')
                    ->form([
                        DatePicker::make('tgl')
                            ->default(now())
                            ->label('Tanggal Absensi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['tgl'],
                                fn (Builder $query, $date): Builder => $query->whereHas('absensis', function($query) use ($date) {
                                    $query->where('tgl', $date);
                                }),
                            );

                        Session::put('filteredDate', $data['tgl']);
                        return $query;
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\CreateAction::make()
                //     ->label('Add')
                //     ->icon('heroicon-o-plus'),
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $date = Session::get('filteredDate') ?? Carbon::now()->toDateString();
                        $absensi = Absensi::where('user_id', $data['id'])->where('tgl', $date)->first();

                        Session::put('user_id', $data['id']);
                        Session::put('data_id', $absensi['id']);

                        $data['jam_masuk'] = $absensi['jam_masuk'];
                        $data['jam_keluar'] = $absensi['jam_keluar'];
                        $data['status'] = $absensi['status'];
                        
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Session::get('user_id');
                        $data['assigned_by'] = auth()->user()->id;
                
                        return $data;
                    })
                    ->using(function (User $record, array $data): User {
                        $id = Session::get('data_id');
                        $arr = [
                            'jam_masuk' => $data['jam_masuk'],
                            'jam_keluar' => $data['jam_keluar'],
                            'status' => $data['status']
                        ];

                        if($arr['status'] == 'alpha' || $arr['status'] == 'izin' || $arr['status'] == 'sakit') {
                            $arr = array_merge($arr , ['jam_masuk' => '', 'jam_keluar' => '']);
                        }
                        
                        Absensi::find($id)->update($arr);
                 
                        return $record;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAbsensis::route('/'),
            // 'create' => Pages\CreateAbsensi::route('/create'),
            // 'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
