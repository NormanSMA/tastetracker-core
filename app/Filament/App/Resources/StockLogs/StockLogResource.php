<?php

namespace App\Filament\App\Resources\StockLogs;

use App\Filament\App\Resources\StockLogs\Pages\CreateStockLog;
use App\Filament\App\Resources\StockLogs\Pages\EditStockLog;
use App\Filament\App\Resources\StockLogs\Pages\ListStockLogs;
use App\Filament\App\Resources\StockLogs\Schemas\StockLogForm;
use App\Filament\App\Resources\StockLogs\Tables\StockLogsTable;
use App\Models\StockLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockLogResource extends Resource
{
    protected static ?string $model = StockLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $modelLabel = 'Movimiento de Stock';

    protected static ?string $pluralModelLabel = 'Movimientos de Stock';

    protected static ?string $navigationLabel = 'Kardex';

    public static function form(Schema $schema): Schema
    {
        return StockLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockLogs::route('/'),
        ];
    }
}
