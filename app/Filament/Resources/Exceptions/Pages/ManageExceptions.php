<?php

namespace App\Filament\Resources\Exceptions\Pages;

use App\Filament\Resources\Exceptions\ExceptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageExceptions extends ManageRecords
{
    protected static string $resource = ExceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
