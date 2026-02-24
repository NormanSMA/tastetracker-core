<?php

namespace App\Filament\App\Resources\Ingredients;

use App\Filament\App\Resources\Ingredients\Pages\CreateIngredient;
use App\Filament\App\Resources\Ingredients\Pages\EditIngredient;
use App\Filament\App\Resources\Ingredients\Pages\ListIngredients;
use App\Models\Ingredient;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $modelLabel = 'Ingrediente';

    protected static ?string $pluralModelLabel = 'Ingredientes';

    protected static ?string $navigationLabel = 'Ingredientes';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del ingrediente')
                    ->icon('heroicon-o-beaker')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del ingrediente')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre comercial del ingrediente')
                            ->columnSpan(2),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Seleccionar categoría'),
                        Select::make('unit')
                            ->label('Unidad de medida')
                            ->options([
                                'gramos' => 'Gramos (g)',
                                'ml' => 'Mililitros (ml)',
                                'unidades' => 'Unidades (u)',
                            ])
                            ->required()
                            ->default('unidades'),
                        TextInput::make('min_stock')
                            ->label('Stock mínimo de alerta')
                            ->numeric()
                            ->default(0)
                            ->placeholder('Cantidad mínima antes de alerta')
                            ->helperText('Se notificará cuando el stock caiga por debajo de este valor'),
                        Toggle::make('is_active')
                            ->label('Ingrediente activo')
                            ->helperText('Desactivar para pausar sin eliminar del sistema')
                            ->default(true),
                    ]),
                Section::make('Nivel de stock')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label('Stock actual')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Solo modificable mediante ajustes desde la tabla')
                            ->default(0),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['category']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por nombre o categor\u00eda...')
            ->columns([
                TextColumn::make('name')
                    ->label('Ingrediente')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => $record->category?->name ?? ''),
                TextColumn::make('stock_quantity')
                    ->label('Stock actual')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->badge()
                    ->icon(fn ($record): string => $record->stock_quantity <= $record->min_stock
                        ? 'heroicon-o-exclamation-triangle'
                        : 'heroicon-o-check-circle')
                    ->color(fn ($record): string => $record->stock_quantity <= $record->min_stock
                        ? 'danger'
                        : 'success')
                    ->suffix(fn ($record): string => ' '.$record->unit),
                TextColumn::make('min_stock')
                    ->label('Mínimo')
                    ->numeric(decimalPlaces: 2)
                    ->color('gray'),
                TextColumn::make('unit')
                    ->label('Unidad')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Estado')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('stock_status')
                    ->label('Estado de stock')
                    ->options([
                        'critical' => 'Stock cr\u00edtico',
                        'healthy' => 'Stock saludable',
                        'inactive' => 'Inactivo',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'critical' => $query->where('is_active', true)->whereColumn('stock_quantity', '<=', 'min_stock'),
                            'healthy' => $query->where('is_active', true)->whereColumn('stock_quantity', '>', 'min_stock'),
                            'inactive' => $query->where('is_active', false),
                            default => $query,
                        };
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('adjustStock')
                    ->label('Ajustar stock')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('warning')
                    ->form([
                        Select::make('type')
                            ->label('Tipo de movimiento')
                            ->options([
                                'add' => 'Entrada (sumar al stock)',
                                'subtract' => 'Salida (restar del stock)',
                            ])
                            ->required()
                            ->default('add'),
                        TextInput::make('amount')
                            ->label('Cantidad')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->placeholder('Cantidad a ajustar'),
                        Select::make('reason')
                            ->label('Motivo del ajuste')
                            ->options([
                                'Compra a proveedor' => 'Compra a proveedor',
                                'Merma / Desperdicio' => 'Merma / Desperdicio',
                                'Ajuste de inventario' => 'Ajuste de inventario',
                                'Venta / Consumo' => 'Venta / Consumo',
                                'Devolución' => 'Devolución',
                                'Transferencia entre sucursales' => 'Transferencia entre sucursales',
                            ])
                            ->required()
                            ->placeholder('Seleccionar motivo del movimiento')
                            ->searchable(),
                    ])
                    ->action(function (array $data, $record): void {
                        $amount = $data['type'] === 'subtract'
                            ? -abs((float) $data['amount'])
                            : abs((float) $data['amount']);

                        $record->adjustStock($amount, $data['reason']);

                        Notification::make()
                            ->title('Stock actualizado')
                            ->body("Nuevo stock de {$record->name}: {$record->stock_quantity} {$record->unit}")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIngredients::route('/'),
            'create' => CreateIngredient::route('/create'),
            'edit' => EditIngredient::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
