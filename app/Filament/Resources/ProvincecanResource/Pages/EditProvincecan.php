<?php

namespace App\Filament\Resources\ProvincecanResource\Pages;

use App\Filament\Resources\ProvincecanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvincecan extends EditRecord
{
    protected static string $resource = ProvincecanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
