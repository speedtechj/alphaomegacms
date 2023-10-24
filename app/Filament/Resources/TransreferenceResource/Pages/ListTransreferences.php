<?php

namespace App\Filament\Resources\TransreferenceResource\Pages;

use App\Filament\Resources\TransreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransreferences extends ListRecords
{
    protected static string $resource = TransreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
