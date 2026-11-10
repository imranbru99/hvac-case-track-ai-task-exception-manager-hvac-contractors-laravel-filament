<?php

namespace App\Filament\Resources\Technicians\Pages;

use App\Filament\Resources\Technicians\TechnicianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTechnicians extends ManageRecords
{
    protected static string $resource = TechnicianResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
