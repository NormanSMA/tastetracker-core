<?php

namespace App\Filament\App\Resources\Products\Pages;

use App\Filament\App\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['branch_id'] = auth()->user()->branch_id;

        return $data;
    }

    protected function afterCreate(): void
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
}
