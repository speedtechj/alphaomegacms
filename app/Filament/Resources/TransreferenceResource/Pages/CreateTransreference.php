<?php

namespace App\Filament\Resources\TransreferenceResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransreference extends CreateRecord
{
    protected static string $resource = TransreferenceResource::class;
    
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['user_id'] = auth()->id();
        
    //     return $data;

    // }
    protected function getRedirectUrl(): string
    {
        return TransactionResource::getUrl('index');
    }
}
