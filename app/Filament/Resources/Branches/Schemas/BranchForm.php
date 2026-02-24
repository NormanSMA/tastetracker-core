<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->icon('heroicon-o-building-storefront')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de la sucursal')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Sucursal Central')
                            ->columnSpan(2),
                        TextInput::make('address')
                            ->label('Dirección / Ubicación')
                            ->maxLength(255)
                            ->placeholder('Ej: Km 5 Carretera Norte, Managua')
                            ->columnSpan(2),
                        TextInput::make('city')
                            ->label('Ciudad / Municipio')
                            ->maxLength(100)
                            ->placeholder('Ej: Managua'),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Ej: +505 2222-3333'),
                        TextInput::make('email')
                            ->label('Correo de la sucursal')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Ej: central@tastetracker.com'),
                        Toggle::make('is_active')
                            ->label('Sucursal activa')
                            ->helperText('Desactiva para ocultar sin eliminar')
                            ->default(true),
                    ]),
                Section::make('Notas internas')
                    ->icon('heroicon-o-document-text')
                    ->collapsed()
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notas')
                            ->placeholder('Ej: Cerrado los domingos. Tiene estacionamiento.')
                            ->rows(3),
                    ]),
            ]);
    }
}
