<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\StockHistory;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\StockHistoriesRelationManagerResource\RelationManagers\StockHistoriesRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('tambahStok')
                ->label('Tambah Stok')
                ->icon('heroicon-o-plus')
                ->form([
                    Forms\Components\TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (array $data, Product $record): void {
                    $record->increment('stock', $data['jumlah']);

                    StockHistory::create([
                        'product_id' => $record->id_product,
                        'user_id' => Filament::auth()->user()->id,
                        'type' => 'in',
                        'amount' => $data['jumlah'],
                        'note' => 'Tambah stok via panel',
                    ]);
                    
                    Notification::make()
                        ->title('Stok berhasil ditambah')
                        ->success()
                        ->send();
                })
                ->color('success'),

            Action::make('kurangiStok')
                ->label('Kurangi Stok')
                ->icon('heroicon-o-minus')
                ->form([
                    Forms\Components\TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (array $data, Product $record): void {
                    if ($data['jumlah'] > $record->stock) {
                        Notification::make()
                            ->title('Jumlah melebihi stok saat ini!')
                            ->danger()
                            ->send();
                        return;
                    }
                
                    $record->decrement('stock', $data['jumlah']);
                    StockHistory::create([
                        'product_id' => $record->id_product,
                        'user_id' => Filament::auth()->user()->id,
                        'type' => 'out',
                        'amount' => $data['jumlah'],
                        'note' => 'Kurangi stok via panel',
                    ]);
                    Notification::make()
                        ->title('Stok berhasil dikurangi')
                        ->success()
                        ->send();
                })
                ->color('danger'),
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
            StockHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
