<?php

namespace App\Filament\Resources\SongRequestResource\Pages;

use App\Filament\Resources\SongRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSongRequest extends EditRecord
{
    protected static string $resource = SongRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
