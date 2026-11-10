<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\ManageTasks;
use App\Models\Task;
use App\Services\CaseWorkflowService;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Case Desk';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'task_number';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('sla_tasks');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('task_number')->disabled()->dehydrated(false),
            TextInput::make('title')->required()->maxLength(255),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            TextInput::make('category'),
            Select::make('priority')->options([
                'urgent' => 'Urgent',
                'high' => 'High',
                'normal' => 'Normal',
                'low' => 'Low',
            ])->required(),
            Select::make('status')->options([
                'draft' => 'Draft',
                'open' => 'Open',
                'in_progress' => 'In Progress',
                'blocked' => 'Blocked',
                'resolved' => 'Resolved',
                'cancelled' => 'Cancelled',
            ])->required(),
            Select::make('assignee_id')->relationship('assignee', 'name')->searchable()->preload(),
            Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload(),
            TextInput::make('sla_hours')->numeric()->required(),
            DateTimePicker::make('sla_due_at'),
            Toggle::make('sla_breached'),
            Textarea::make('resolution_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task_number')->searchable()->sortable(),
                TextColumn::make('title')->limit(40)->searchable(),
                TextColumn::make('priority')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')->badge(),
                TextColumn::make('assignee.name')->label('Assignee')->placeholder('—'),
                TextColumn::make('sla_due_at')->dateTime()->sortable()
                    ->color(fn (Task $record) => $record->isOverdue() ? 'danger' : null),
                IconColumn::make('sla_breached')->boolean()->label('Breached'),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('sla_due_at')
            ->filters([
                SelectFilter::make('status')->options([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'blocked' => 'Blocked',
                    'resolved' => 'Resolved',
                ]),
                SelectFilter::make('priority')->options([
                    'urgent' => 'Urgent',
                    'high' => 'High',
                    'normal' => 'Normal',
                    'low' => 'Low',
                ]),
            ])
            ->recordActions([
                Action::make('exception')
                    ->label('Open Exception')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (Task $record) => ! in_array($record->status, ['resolved', 'cancelled'], true)
                        && app(ModuleManager::class)->enabled('exception_manager'))
                    ->form([
                        Select::make('type')->options([
                            'sla_breach' => 'SLA Breach',
                            'parts_delay' => 'Parts Delay',
                            'safety_hold' => 'Safety Hold',
                            'ops_block' => 'Ops Block',
                            'callback' => 'Callback',
                        ])->default('sla_breach')->required(),
                        TextInput::make('title')->required(),
                        Textarea::make('details'),
                    ])
                    ->action(function (Task $record, array $data) {
                        $exc = app(CaseWorkflowService::class)->openException($record, auth()->user(), $data);
                        Notification::make()->title('Exception '.$exc->exception_number.' opened')->warning()->send();
                    }),
                Action::make('escalate')
                    ->label('Escalate')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->visible(fn () => app(ModuleManager::class)->enabled('escalation_engine'))
                    ->form([
                        TextInput::make('reason')->required(),
                        Textarea::make('notes'),
                    ])
                    ->action(function (Task $record, array $data) {
                        $esc = app(CaseWorkflowService::class)->escalate($record, auth()->user(), $data);
                        Notification::make()->title('Escalation '.$esc->escalation_number.' created')->success()->send();
                    }),
                Action::make('resolve')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Task $record) => ! in_array($record->status, ['resolved', 'cancelled'], true))
                    ->form([
                        Textarea::make('notes')->label('Resolution notes'),
                    ])
                    ->action(function (Task $record, array $data) {
                        app(CaseWorkflowService::class)->resolveTask($record, auth()->user(), $data['notes'] ?? null);
                        Notification::make()->title('Task resolved')->success()->send();
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
            'index' => ManageTasks::route('/'),
        ];
    }
}
