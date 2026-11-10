<?php

namespace App\Filament\Resources\Exceptions;

use App\Filament\Resources\Exceptions\Pages\ManageExceptions;
use App\Models\CaseException;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ExceptionResource extends Resource
{
    protected static ?string $model = CaseException::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Case Desk';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Exceptions';

    protected static ?string $modelLabel = 'Exception';

    protected static ?string $recordTitleAttribute = 'exception_number';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('exception_manager');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('exception_number')->disabled()->dehydrated(false),
            Select::make('task_id')->relationship('task', 'task_number')->searchable()->preload(),
            Select::make('type')->options([
                'sla_breach' => 'SLA Breach',
                'parts_delay' => 'Parts Delay',
                'safety_hold' => 'Safety Hold',
                'ops_block' => 'Ops Block',
                'callback' => 'Callback',
                'billing' => 'Billing',
            ])->required(),
            Select::make('severity')->options([
                'critical' => 'Critical',
                'high' => 'High',
                'medium' => 'Medium',
                'low' => 'Low',
            ])->required(),
            Select::make('status')->options([
                'open' => 'Open',
                'investigating' => 'Investigating',
                'escalated' => 'Escalated',
                'resolved' => 'Resolved',
                'closed' => 'Closed',
            ])->required(),
            TextInput::make('title')->required(),
            Textarea::make('details')->columnSpanFull(),
            Select::make('owner_id')->relationship('owner', 'name')->searchable()->preload(),
            Textarea::make('resolution')->columnSpanFull(),
            DateTimePicker::make('resolved_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exception_number')->searchable()->sortable(),
                TextColumn::make('task.task_number')->label('Task')->placeholder('—'),
                TextColumn::make('type')->badge(),
                TextColumn::make('severity')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')->badge(),
                TextColumn::make('title')->limit(36),
                TextColumn::make('owner.name')->label('Owner')->placeholder('—'),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'open' => 'Open',
                    'investigating' => 'Investigating',
                    'escalated' => 'Escalated',
                    'resolved' => 'Resolved',
                ]),
                SelectFilter::make('type')->options([
                    'sla_breach' => 'SLA Breach',
                    'parts_delay' => 'Parts Delay',
                    'safety_hold' => 'Safety Hold',
                    'ops_block' => 'Ops Block',
                ]),
            ])
            ->recordActions([
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
            'index' => ManageExceptions::route('/'),
        ];
    }
}
