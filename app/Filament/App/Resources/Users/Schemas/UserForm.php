<?php

namespace App\Filament\App\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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
                            ->placeholder('Nombre y apellido del empleado')
                            ->columnSpan(2),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Correo de contacto'),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->rule(Password::default())
                            ->helperText('Dejar vacío para mantener la contraseña actual'),
                        TextInput::make('pin')
                            ->label('PIN de Acceso (4-6 dígitos)')
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(6)
                            ->placeholder('1234')
                            ->helperText('Se usará para el acceso rápido en el Punto de Venta (POS)')
                            ->columnSpan(2),
                        Select::make('gender')
                            ->label('Género')
                            ->options([
                                'masculino' => 'Masculino',
                                'femenino' => 'Femenino',
                                'otro' => 'Otro',
                            ])
                            ->native(false),
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
                            ->helperText('Se usará para identificar al personal en el POS'),
                    ]),
                Section::make('Rol y asignación')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->label('Rol')
                            ->options([
                                'waiter' => 'Mesero',
                                'kitchen' => 'Cocina',
                                'delivery' => 'Repartidor',
                            ])
                            ->required()
                            ->default('waiter')
                            ->native(false),
                        Hidden::make('branch_id')
                            ->default(fn() => auth()->user()->branch_id),
                        Toggle::make('is_active')
                            ->label('Usuario activo')
                            ->helperText('Desactivar para bloquear el acceso sin eliminar')
                            ->default(true)
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
