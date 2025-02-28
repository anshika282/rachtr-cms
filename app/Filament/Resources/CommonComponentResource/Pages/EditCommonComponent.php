<?php

namespace App\Filament\Resources\CommonComponentResource\Pages;

use App\Filament\Resources\CommonComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommonComponent extends EditRecord
{
    protected static string $resource = CommonComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
