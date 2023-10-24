<?php

namespace App\Filament\Resources\PhilcityResource\Pages;

use App\Filament\Resources\PhilcityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhilcities extends ListRecords
{
    protected static string $resource = PhilcityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
