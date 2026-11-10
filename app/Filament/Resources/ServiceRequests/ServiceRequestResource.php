<?php

namespace App\Filament\Resources\ServiceRequests;

use App\Filament\Resources\ServiceRequests\Pages\ManageServiceRequests;
use App\Models\ServiceRequest;
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
use Filament\Tables\Table;
use UnitEnum;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Field Operations';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('service_requests');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('request_number')->disabled()->dehydrated(false),
            Select::make('task_id')->relationship('task', 'task_number')->searchable()->preload(),
            Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload(),
            TextInput::make('customer_name')->required(),
            TextInput::make('address'),
            TextInput::make('service_type'),
            Select::make('priority')->options([
                'urgent' => 'Urgent',
                'high' => 'High',
                'normal' => 'Normal',
                'low' => 'Low',
            ])->required(),
            Select::make('status')->options([
                'intake' => 'Intake',
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->required(),
            DateTimePicker::make('scheduled_at'),
            Textarea::make('notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_number')->searchable(),
                TextColumn::make('customer_name')->searchable(),
                TextColumn::make('service_type')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('scheduled_at')->dateTime()->placeholder('—'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageServiceRequests::route('/')];
    }
}
