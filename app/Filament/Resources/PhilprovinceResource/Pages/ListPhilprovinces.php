<?php

namespace App\Filament\Resources\PhilprovinceResource\Pages;

use App\Filament\Resources\PhilprovinceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhilprovinces extends ListRecords
{
    protected static string $resource = PhilprovinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
