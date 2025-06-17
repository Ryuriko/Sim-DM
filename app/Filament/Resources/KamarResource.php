<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Foto;
use Filament\Tables;
use App\Models\Kamar;
use Filament\Forms\Form;
use App\Models\TipeKamar;
use Filament\Tables\Table;
use App\Models\FasilitasKamar;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KamarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KamarResource\RelationManagers;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $pluralModelLabel = 'Kamar';

    protected static ?string $modelLabel = 'Kamar';

    protected static ?string $navigationLabel = 'Kamar';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-m-building-office-2';

    protected static ?string $navigationGroup = 'Hotel';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no')
                    ->label('No Kamar')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\Select::make('tipe_kamar_id')
                    ->label('Tipe Kamar')
                    ->options(TipeKamar::query()->pluck('nama', 'id'))
                    ->required(),
                // Forms\Components\TextInput::make('lantai')
                //     ->numeric()
                //     ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'terisi' => 'Terisi',
                        'dibersihkan' => 'Dibersihkan',
                        'tidak tersedia' => 'Tidak Tersedia',
                    ])
                    ->required(),
                Forms\Components\Select::make('fasilitas')
                    ->multiple()
                    ->relationship('fasilitas')
                    ->hint('Bisa lebih dari satu')
                    ->columnSpanFull()
                    ->options(FasilitasKamar::query()->pluck('nama', 'id')),
                Forms\Components\FileUpload::make('foto')
                    ->label('Thumbnail')
                    ->directory('hotel-kamar/thumbnail')
                    ->image()
                    ->imageEditor()
                    ->required(),
                Forms\Components\FileUpload::make('fotos')
                    ->label('Foto')
                    ->hint('Bisa lebih dari satu gambar')
                    ->multiple()
                    ->directory('hotel-kamar/photos')
                    ->image()
                    ->imageEditor(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->searchable()
                    ->label('No Kamar'),
                Tables\Columns\TextColumn::make('tipe.nama')
                    ->label('Tipe Kamar'),
                // Tables\Columns\TextColumn::make('lantai'),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords(strtolower($state)))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'terisi' => 'gray',
                        'dibersihkan' => 'warning',
                        'tidak tersedia' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['fotos'] = Kamar::find($data['id'])->fotos->pluck('path');
                
                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        unset($data['fasilitas']);
                        unset($data['fotos']);

                        $record->update($data);
                
                        return $record;
                    })
                    ->after(function ($record, $data) {
                        $fotos = $data['fotos'] ?? '';
                        if($fotos != '') {
                            $record->fotos()->detach();

                            foreach ($fotos as $foto) {
                                $createFoto = Foto::updateOrCreate([
                                    'path' => $foto
                                ]);
                                
                                $record->fotos()->attach($createFoto->id);
                            }

                        }
                    }),
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
            'index' => Pages\ListKamars::route('/'),
            'view' => Pages\ViewKamar::route('/{record}'),
            // 'create' => Pages\CreateKamar::route('/create'),
            // 'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
