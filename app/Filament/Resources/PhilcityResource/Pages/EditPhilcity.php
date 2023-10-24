<?php

namespace App\Filament\Resources\PhilcityResource\Pages;

use App\Filament\Resources\PhilcityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhilcity extends EditRecord
{
    protected static string $resource = PhilcityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
