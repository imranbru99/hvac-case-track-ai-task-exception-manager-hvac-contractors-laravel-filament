<?php

namespace App\Filament\Resources\CaseMessages;

use App\Filament\Resources\CaseMessages\Pages\ManageCaseMessages;
use App\Models\CaseMessage;
use App\Services\AiTaskAssistService;
use App\Services\CaseWorkflowService;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CaseMessageResource extends Resource
{
    protected static ?string $model = CaseMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Case Desk';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'reference';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('message_intake');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Case Message')->schema([
                Select::make('channel')->options([
                    'email' => 'Email',
                    'sms' => 'SMS',
                    'phone' => 'Phone',
                    'portal' => 'Portal',
                    'ops_note' => 'Ops Note',
                    'jobber' => 'Jobber',
                    'servicetitan' => 'ServiceTitan',
                ])->required()->native(false),
                Select::make('direction')->options([
                    'inbound' => 'Inbound',
                    'internal' => 'Internal',
                ])->required()->native(false),
                TextInput::make('from_name')->required()->maxLength(255),
                TextInput::make('from_email')->email(),
                TextInput::make('from_phone')->tel(),
                TextInput::make('subject')->maxLength(255),
                Textarea::make('body')->required()->rows(5)->columnSpanFull(),
                Toggle::make('consent_email')->label('Email consent'),
                Toggle::make('consent_sms')->label('SMS consent'),
                Select::make('status')->options([
                    'new' => 'New',
                    'classified' => 'Classified',
                    'pending_approval' => 'Pending Approval',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'tasked' => 'Tasked',
                ])->native(false),
                Select::make('assigned_to')->relationship('assignee', 'name')->searchable()->preload(),
            ])->columns(2),
            Section::make('AI Assist')->schema([
                TextInput::make('ai_category')->disabled(),
                TextInput::make('ai_priority')->disabled(),
                TextInput::make('ai_score')->numeric()->disabled(),
                TextInput::make('suggested_sla_hours')->numeric()->disabled()->label('Suggested SLA (h)'),
                Textarea::make('ai_summary')->disabled()->columnSpanFull(),
            ])->columns(4)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->searchable()->sortable(),
                TextColumn::make('channel')->badge(),
                TextColumn::make('from_name')->searchable(),
                TextColumn::make('ai_category')->label('Category')->badge()->placeholder('—'),
                TextColumn::make('ai_priority')->label('Priority')->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('suggested_sla_hours')->label('SLA h')->sortable(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending_approval' => 'warning',
                        'approved', 'tasked' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('consent_email')->boolean()->label('Email OK'),
                TextColumn::make('created_at')->dateTime()->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'new' => 'New',
                    'classified' => 'Classified',
                    'pending_approval' => 'Pending Approval',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'tasked' => 'Tasked',
                ]),
                SelectFilter::make('channel')->options([
                    'email' => 'Email',
                    'sms' => 'SMS',
                    'phone' => 'Phone',
                    'portal' => 'Portal',
                    'ops_note' => 'Ops Note',
                ]),
            ])
            ->recordActions([
                Action::make('classify')
                    ->label('AI Assist')
                    ->icon('heroicon-o-sparkles')
                    ->visible(fn (CaseMessage $record) => in_array($record->status, ['new', 'classified'], true))
                    ->action(function (CaseMessage $record) {
                        app(AiTaskAssistService::class)->classify($record);
                        Notification::make()->title('Message classified')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Approve → Task')
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->visible(fn (CaseMessage $record) => in_array($record->status, ['pending_approval', 'classified', 'approved'], true)
                        && $record->tasks()->count() === 0)
                    ->requiresConfirmation()
                    ->action(function (CaseMessage $record) {
                        $task = app(CaseWorkflowService::class)->approve($record, auth()->user());
                        Notification::make()->title('Task '.$task->task_number.' created with SLA')->success()->send();
                    }),
                Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (CaseMessage $record) => in_array($record->status, ['pending_approval', 'classified'], true))
                    ->form([
                        Textarea::make('reason')->label('Rejection reason')->required(),
                    ])
                    ->action(function (CaseMessage $record, array $data) {
                        app(CaseWorkflowService::class)->reject($record, auth()->user(), $data['reason']);
                        Notification::make()->title('Message rejected')->warning()->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCaseMessages::route('/'),
        ];
    }
}
