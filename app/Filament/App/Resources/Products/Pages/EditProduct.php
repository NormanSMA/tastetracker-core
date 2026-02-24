<?php

namespace App\Filament\App\Resources\Products\Pages;

use App\Filament\App\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['recipe'] = $this->record->ingredients->map(fn ($ingredient) => [
            'ingredient_id' => $ingredient->id,
            'quantity' => $ingredient->pivot->quantity,
        ])->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $recipe = $this->data['recipe'] ?? [];

        $syncData = [];
        foreach ($recipe as $item) {
            if (! empty($item['ingredient_id']) && ! empty($item['quantity'])) {
                $syncData[$item['ingredient_id']] = ['quantity' => $item['quantity']];
            }
        }

        $this->record->ingredients()->sync($syncData);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
