<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de acceso')
                    ->icon('heroicon-o-key')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nombre y apellido del usuario')
                            ->columnSpan(2),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Correo institucional del usuario'),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(Password::default())
                            ->helperText('Dejar vacío para mantener la contraseña actual')
                            ->placeholder('Contraseña segura'),
                        Select::make('gender')
                            ->label('Género')
                            ->options([
                                'masculino' => 'Masculino',
                                'femenino' => 'Femenino',
                                'otro' => 'Otro',
                            ])
                            ->native(false)
                            ->placeholder('Seleccionar género'),
                        FileUpload::make('photo')
                            ->label('Foto de perfil')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('user-photos')
                            ->saveUploadedFileUsing(function ($file) {
                                return \App\Services\ImageOptimizer::optimizeToWebp(
                                    $file,
                                    'user-photos',
                                    width: 400
                                );
                            })
                            ->helperText('Opcional. Se usará para identificar al personal en el POS'),
                    ]),
                Section::make('Rol y asignación')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->label('Rol')
                            ->options([
                                'admin' => 'Administrador (IT)',
                                'manager' => 'Gerente de Sucursal',
                                'waiter' => 'Mesero',
                                'kitchen' => 'Cocina',
                                'delivery' => 'Repartidor',
                            ])
                            ->required()
                            ->default('waiter')
                            ->native(false),
                        Select::make('branch_id')
                            ->label('Sucursal asignada')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Sin sucursal (solo administradores IT)')
                            ->helperText('Los administradores IT pueden operar sin sucursal'),
                        Toggle::make('is_active')
                            ->label('Usuario activo')
                            ->helperText('Desactivar para bloquear el acceso sin eliminar')
                            ->default(true)
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
