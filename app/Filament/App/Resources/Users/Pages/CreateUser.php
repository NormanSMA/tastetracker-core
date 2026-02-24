<?php

namespace App\Filament\App\Resources\Users\Pages;

use App\Filament\App\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['branch_id'] = auth()->user()->branch_id;

        return $data;
    }
}
