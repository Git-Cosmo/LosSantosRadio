<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the author to the current user if not specified
        if (empty($data['author_id'])) {
            $data['author_id'] = auth()->id();
        }

        return $data;
    }
}
