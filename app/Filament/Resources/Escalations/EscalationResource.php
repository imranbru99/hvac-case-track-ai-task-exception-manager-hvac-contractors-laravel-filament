<?php

namespace App\Filament\Resources\Escalations;

use App\Filament\Resources\Escalations\Pages\ManageEscalations;
use App\Models\Escalation;
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

class EscalationResource extends Resource
{
    protected static ?string $model = Escalation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Case Desk';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'escalation_number';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('escalation_engine');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('escalation_number')->disabled()->dehydrated(false),
            Select::make('task_id')->relationship('task', 'task_number')->searchable()->preload(),
            Select::make('exception_id')->relationship('exception', 'exception_number')->searchable()->preload(),
            Select::make('from_user_id')->relationship('fromUser', 'name')->searchable()->preload(),
            Select::make('to_user_id')->relationship('toUser', 'name')->searchable()->preload(),
            TextInput::make('level')->numeric()->default(1),
            TextInput::make('reason')->required(),
            Select::make('status')->options([
                'pending' => 'Pending',
                'acknowledged' => 'Acknowledged',
                'resolved' => 'Resolved',
                'cancelled' => 'Cancelled',
            ])->required(),
            Textarea::make('notes')->columnSpanFull(),
            DateTimePicker::make('acknowledged_at'),
            DateTimePicker::make('resolved_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('escalation_number')->searchable()->sortable(),
                TextColumn::make('task.task_number')->label('Task')->placeholder('—'),
                TextColumn::make('level')->badge(),
                TextColumn::make('reason')->limit(40),
                TextColumn::make('toUser.name')->label('To')->placeholder('—'),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'acknowledged' => 'info',
                        'resolved' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'acknowledged' => 'Acknowledged',
                    'resolved' => 'Resolved',
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
            'index' => ManageEscalations::route('/'),
        ];
    }
}
