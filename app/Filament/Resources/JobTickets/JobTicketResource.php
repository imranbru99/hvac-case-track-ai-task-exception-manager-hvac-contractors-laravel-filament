<?php

namespace App\Filament\Resources\JobTickets;

use App\Filament\Resources\JobTickets\Pages\ManageJobTickets;
use App\Models\JobTicket;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JobTicketResource extends Resource
{
    protected static ?string $model = JobTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Field Operations';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('job_tickets');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('ticket_number')->disabled()->dehydrated(false),
            Select::make('service_request_id')->relationship('serviceRequest', 'request_number')->searchable()->preload(),
            Select::make('technician_id')->relationship('technician', 'name')->searchable()->preload(),
            Select::make('status')->options([
                'draft' => 'Draft',
                'dispatched' => 'Dispatched',
                'on_site' => 'On Site',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->required(),
            DateTimePicker::make('scheduled_start'),
            DateTimePicker::make('scheduled_end'),
            TagsInput::make('safety_checklist')->placeholder('Add safety item'),
            Textarea::make('completion_notes')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')->searchable(),
                TextColumn::make('serviceRequest.request_number')->label('Service Request'),
                TextColumn::make('technician.name')->label('Technician'),
                TextColumn::make('status')->badge(),
                TextColumn::make('scheduled_start')->dateTime(),
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
        return ['index' => ManageJobTickets::route('/')];
    }
}
