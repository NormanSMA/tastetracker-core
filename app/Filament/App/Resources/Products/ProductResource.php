<?php

namespace App\Filament\App\Resources\Products;

use App\Filament\App\Resources\Products\Pages\CreateProduct;
use App\Filament\App\Resources\Products\Pages\EditProduct;
use App\Filament\App\Resources\Products\Pages\ListProducts;
use App\Models\Ingredient;
use App\Models\Product;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del producto')
                    ->icon('heroicon-o-shopping-bag')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del producto')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre comercial del producto')
                            ->columnSpan(2),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->placeholder('Descripción del producto para el menú')
                            ->rows(3)
                            ->columnSpan(2),
                        TextInput::make('price')
                            ->label('Precio de venta')
                            ->numeric()
                            ->required()
                            ->prefix('C$')
                            ->step(0.01)
                            ->placeholder('Precio unitario'),
                        FileUpload::make('photo')
                            ->label('Foto del producto')
                            ->image()
                            ->disk('public')
                            ->directory('product-photos')
                            ->saveUploadedFileUsing(function ($file) {
                                return \App\Services\ImageOptimizer::optimizeToWebp(
                                    $file,
                                    'product-photos',
                                    width: 800
                                );
                            })
                            ->helperText('Imagen para el menú digital'),
                        Toggle::make('is_active')
                            ->label('Producto disponible')
                            ->helperText('Desactivar para retirar del menú sin eliminar')
                            ->default(true)
                            ->columnSpan(2),
                    ]),
                Section::make('Receta (ingredientes)')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->description('Define los ingredientes y cantidades que consume este producto al venderse')
                    ->schema([
                        Repeater::make('recipe')
                            ->label('Ingredientes de la receta')
                            ->schema([
                                Select::make('ingredient_id')
                                    ->label('Ingrediente')
                                    ->options(fn () => Ingredient::query()->where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Seleccionar ingrediente')
                                    ->columnSpan(2),
                                TextInput::make('quantity')
                                    ->label('Cantidad por unidad')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->placeholder('Cantidad consumida por producto vendido'),
                            ])
                            ->columns(3)
                            ->addActionLabel('Agregar ingrediente')
                            ->reorderable(false)
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['ingredients']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Buscar por nombre de producto...')
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => $record->description ? mb_substr($record->description, 0, 50).'...' : ''),
                TextInputColumn::make('price')
                    ->label('Precio (C$)')
                    ->sortable()
                    ->rules(['required', 'numeric', 'min:0.01']),
                TextColumn::make('ingredients_count')
                    ->counts('ingredients')
                    ->label('Ingredientes')
                    ->badge()
                    ->color('info'),
                ToggleColumn::make('is_active')
                    ->label('Disponible')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Disponibilidad')
                    ->trueLabel('Disponibles')
                    ->falseLabel('No disponibles'),
                TrashedFilter::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
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
