<?php

namespace App\Filament\Resources\TransreferenceResource\Pages;

use App\Filament\Resources\TransreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransreference extends EditRecord
{
    protected static string $resource = TransreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
