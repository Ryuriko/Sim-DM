<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Penggajian;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\SettingPenggajian;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Resources\Components\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use App\Http\Services\PenggajianService;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\PenggajianResource\Pages;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $pluralModelLabel = 'Penggajian';

    protected static ?string $modelLabel = 'Penggajian';

    protected static ?string $navigationLabel = 'Penggajian';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-m-users';

    protected static ?string $navigationGroup = 'Karyawan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->readOnly(),
                TextInput::make('bonus')
                    ->required()
                    ->numeric(),
                TextInput::make('potongan')
                    ->required()
                    ->numeric(),
                Textarea::make('ket')
                    ->label('Keterangan')
                    ->placeholder('Silakan masukan keterangan atas potongan gaji dan bonus yang diberikan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereDoesntHave('role', function($query) {
                    $query->where('name', 'sistem');
                })
                ->with('penggajians', function($query) {
                    $tahun = Session::get('filteredTahun') ?? Carbon::now()->year;
                    $bulan = Session::get('filteredBulan') ?? Carbon::now()->locale('id')->translatedFormat('F');

                    $query->where('tahun', $tahun)->where('bulan', $bulan); 
                })
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('role.name')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->color('gray')
                    ->label('Jabatan'),
                TextColumn::make('penggajians.bulan')
                    ->label('Bulan'),
                TextColumn::make('penggajians.tahun')
                    ->label('Tahun'),
                TextColumn::make('penggajians.gaji_pokok')
                    ->label('Gaji Pokok')
                    ->money('Rp.'),
                TextColumn::make('penggajians.bonus')
                    ->label('Bonus')
                    ->money('Rp.'),
                TextColumn::make('penggajians.potongan')
                    ->label('Potongan')
                    ->money('Rp.'),
                TextColumn::make('penggajians.gaji_total')
                    ->label('Gaji Total')
                    ->badge()
                    ->color('gray')
                    ->money('Rp.'),
                TextColumn::make('penggajians.ket')
                    ->label('Keterangan')
                    ->words(5)
                    ->lineClamp(3)
                
            ])
            ->filters([
                Filter::make('bulan')
                    ->form([
                        Select::make('bulan')
                            ->label('Bulan')
                            ->options( function () {
                                $bulan = [];
                                for ($i=1; $i <= 12; $i++) { 
                                    $bulan[Carbon::create(2025, $i, 1)->locale('id')->translatedFormat('F')] = Carbon::create(2025, $i, 1)->locale('id')->translatedFormat('F');
                                }

                                return $bulan;
                            })
                            ->default(Carbon::now()->locale('id')->translatedFormat('F')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['bulan'],
                                fn (Builder $query, $bulan): Builder => $query->whereHas('penggajians', function($query) use ($bulan) {
                                    $query->where('bulan', $bulan);
                                }),
                            );

                        Session::put('filteredBulan', $data['bulan']);
                        return $query;
                    }),
                Filter::make('tahun')
                    ->form([
                        Select::make('tahun')
                            ->options([
                                Carbon::now()->year => Carbon::now()->year,
                                Carbon::now()->year - 1 => Carbon::now()->year -1,
                            ])
                            ->default(Carbon::now()->year)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query = $query
                            ->when(
                                $data['tahun'],
                                fn (Builder $query, $tahun): Builder => $query->whereHas('penggajians', function($query) use ($tahun) {
                                    $query->where('tahun', $tahun);
                                }),
                            );

                        Session::put('filteredTahun', $data['tahun']);
                        return $query;
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $bulan = Session::get('filteredBulan') ?? Carbon::now()->locale('id')->translatedFormat('F');
                        $tahun = Session::get('filteredTahun') ?? Carbon::now()->year;
                        $peggajian = Penggajian::where('user_id', $data['id'])->where('tahun', $tahun)->where('bulan', $bulan)->first();

                        Session::put('user_id', $data['id']);
                        Session::put('data_id', $peggajian['id']);
                        Session::put('gaji_pokok', $peggajian['gaji_pokok']);

                        $data['bonus'] = $peggajian['bonus'];
                        $data['potongan'] = $peggajian['potongan'];
                        $data['ket'] = $peggajian['ket'];
                        
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Session::get('user_id');
                
                        return $data;
                    })
                    ->using(function (User $record, array $data): User {
                        $id = Session::get('data_id');
                        $gajiTotal = Session::get('gaji_pokok');
                        $gajiTotal = $gajiTotal + $data['bonus'] - $data['potongan'];
                        $arr = [
                            'bonus' => $data['bonus'],
                            'potongan' => $data['potongan'],
                            'gaji_total' => $gajiTotal,
                            'ket' => $data['ket'],
                        ];

                        Penggajian::find($id)->update($arr);
                
                        return $record;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('Sync')
                    ->action(function() {
                        $tahun = Session::get('filteredTahun');
                        $bulan = Session::get('filteredBulan');

                        $opr = PenggajianService::summarize($tahun, $bulan);

                        if($opr['status'] == true) {
                            Notification::make()
                                ->title('Sync Successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Sync Failed')
                                ->danger()
                                ->body($opr['message'])
                                ->send();
                        }
                    })
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
            'index' => Pages\ListPenggajians::route('/'),
            // 'create' => Pages\CreatePenggajian::route('/create'),
            // 'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }

}