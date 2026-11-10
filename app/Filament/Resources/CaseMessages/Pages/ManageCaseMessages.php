<?php

namespace App\Filament\Resources\CaseMessages\Pages;

use App\Filament\Resources\CaseMessages\CaseMessageResource;
use App\Services\CaseWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageCaseMessages extends ManageRecords
{
    protected static string $resource = CaseMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('intake')
                ->label('Intake Message')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    Select::make('channel')->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'phone' => 'Phone',
                        'portal' => 'Portal',
                        'ops_note' => 'Ops Note',
                    ])->default('email')->required(),
                    TextInput::make('from_name')->required(),
                    TextInput::make('from_email')->email(),
                    TextInput::make('from_phone'),
                    TextInput::make('subject'),
                    Textarea::make('body')->required()->rows(4),
                    Toggle::make('consent_email')->default(true),
                    Toggle::make('consent_sms')->default(false),
                ])
                ->action(function (array $data) {
                    $message = app(CaseWorkflowService::class)->intake($data, auth()->user());
                    Notification::make()
                        ->title("Message {$message->reference} classified as {$message->ai_category}")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
