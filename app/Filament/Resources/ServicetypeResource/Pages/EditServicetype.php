<?php

namespace App\Filament\Resources\ServicetypeResource\Pages;

use App\Filament\Resources\ServicetypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicetype extends EditRecord
{
    protected static string $resource = ServicetypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
