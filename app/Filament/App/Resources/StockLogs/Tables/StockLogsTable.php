<?php

namespace App\Filament\App\Resources\StockLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class StockLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha y Hora')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ingrediente')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('type')
                    ->label('Movimiento')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'add' => 'Entrada',
                        'subtract' => 'Salida',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'add' => 'success',
                        'subtract' => 'danger',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->label('Cantidad')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->searchable()
                    ->wrap(),
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsable')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // actions here
            ])
            ->bulkActions([
                // none
            ]);
    }
}
