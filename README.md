# TasteTracker Core — POS OS

> Arquitectura Local-First para gestión de restaurantes, construida sobre Laravel 12 + Filament v3.

## Stack Tecnológico

| Capa          | Tecnología           | Versión  |
| ------------- | -------------------- | -------- |
| Backend       | Laravel Framework    | 12.x     |
| Admin UI      | Filament             | v3       |
| PHP           | PHP                  | 8.2+     |
| Base de Datos | PostgreSQL           | Supabase |
| Cache/Queue   | Redis                | 7.x      |
| Frontend      | Livewire + Alpine.js | v4       |

## Arquitectura

```
tastetracker-api/
├── app/
│   ├── Filament/
│   │   ├── Resources/        # Panel Admin (/admin)
│   │   │   ├── Branches/
│   │   │   └── Users/
│   │   ├── App/              # Panel Operativo (/app)
│   │   │   ├── Resources/
│   │   │   │   ├── Categories/
│   │   │   │   ├── Ingredients/
│   │   │   │   └── Products/
│   │   │   └── Widgets/
│   │   └── Widgets/          # Widgets Admin
│   ├── Models/
│   │   ├── Scopes/           # BranchScope (Multi-tenancy)
│   │   ├── Branch.php
│   │   ├── Category.php
│   │   ├── Ingredient.php
│   │   ├── Product.php
│   │   └── User.php
│   └── Services/
│       ├── ImageOptimizer.php # Conversión WebP
│       └── StockManager.php   # Descuento automático
├── database/migrations/
└── public/image/              # Assets de marca
```

## Paneles

### Panel Admin IT (`/admin`)

- Gestión de sucursales y usuarios
- Dashboard con métricas del sistema (Redis, storage, sucursales)
- Visor de logs (`/log-viewer`)

### Panel Operativo (`/app`)

- Gestión de categorías, ingredientes y productos
- Ajuste de stock con trazabilidad (motivos)
- Alertas de stock bajo en tiempo real
- Multi-tenancy estricto por sucursal (`BranchScope`)

## Características Principales

- **Multi-tenancy**: Aislamiento de datos por sucursal via `BranchScope`
- **Motor de Recetas**: Tabla pivote `product_ingredient` con cantidades por unidad
- **Descuento Automático**: `StockManager::processSale()` descuenta inventario por receta
- **Optimización de Imágenes**: Conversión automática a WebP via `ImageOptimizer`
- **SPA Mode**: Navegación sin recargas de página
- **Eager Loading**: Consultas optimizadas para evitar N+1

## Requisitos

- PHP >= 8.2 con extensiones: `gd`, `pdo_pgsql`, `redis`
- Composer >= 2.x
- Node.js >= 18.x
- PostgreSQL >= 15
- Redis >= 7.x

## Instalación

```bash
git clone <repo-url> tastetracker-api
cd tastetracker-api
composer setup
```

## Desarrollo

```bash
composer run dev
```

## Tests

```bash
php artisan test
```

## Licencia

Propietaria — Todos los derechos reservados.
