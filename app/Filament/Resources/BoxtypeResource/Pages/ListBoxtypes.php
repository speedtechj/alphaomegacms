<?php

namespace App\Filament\Resources\BoxtypeResource\Pages;

use App\Filament\Resources\BoxtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoxtypes extends ListRecords
{
    protected static string $resource = BoxtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
