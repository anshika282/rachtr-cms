<?php

namespace App\Filament\Resources\CommonComponentResource\Pages;

use App\Filament\Resources\CommonComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommonComponents extends ListRecords
{
    protected static string $resource = CommonComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
