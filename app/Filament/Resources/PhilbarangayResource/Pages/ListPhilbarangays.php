<?php

namespace App\Filament\Resources\PhilbarangayResource\Pages;

use App\Filament\Resources\PhilbarangayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhilbarangays extends ListRecords
{
    protected static string $resource = PhilbarangayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
