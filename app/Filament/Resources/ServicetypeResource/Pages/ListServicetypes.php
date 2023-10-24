<?php

namespace App\Filament\Resources\ServicetypeResource\Pages;

use App\Filament\Resources\ServicetypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServicetypes extends ListRecords
{
    protected static string $resource = ServicetypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
