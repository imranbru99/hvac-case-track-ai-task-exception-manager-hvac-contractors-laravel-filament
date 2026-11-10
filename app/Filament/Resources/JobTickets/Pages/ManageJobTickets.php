<?php

namespace App\Filament\Resources\JobTickets\Pages;

use App\Filament\Resources\JobTickets\JobTicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJobTickets extends ManageRecords
{
    protected static string $resource = JobTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
