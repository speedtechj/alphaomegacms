<?php

namespace App\Filament\Resources\SenderResource\Pages;

use App\Filament\Resources\SenderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSender extends CreateRecord
{
    protected static string $resource = SenderResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
