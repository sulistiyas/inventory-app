<?php

namespace App\Filament\Resources\StockHistoriesRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class StockHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'stockHistories';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Riwayat Stok')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Riwayat Stok')
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ])
                    ->label('Tipe'),
                TextColumn::make('amount')->label('Jumlah'),
                TextColumn::make('note')->label('Catatan'),
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('created_at')->since()->label('Tanggal'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
