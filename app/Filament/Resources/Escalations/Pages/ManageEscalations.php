<?php

namespace App\Filament\Resources\Escalations\Pages;

use App\Filament\Resources\Escalations\EscalationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEscalations extends ManageRecords
{
    protected static string $resource = EscalationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
