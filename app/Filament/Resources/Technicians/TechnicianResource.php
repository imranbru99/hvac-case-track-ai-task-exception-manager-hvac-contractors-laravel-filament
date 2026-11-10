<?php

namespace App\Filament\Resources\Technicians;

use App\Filament\Resources\Technicians\Pages\ManageTechnicians;
use App\Models\Technician;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TechnicianResource extends Resource
{
    protected static ?string $model = Technician::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Field Operations';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('field_technicians');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->relationship('user', 'name')->searchable()->preload(),
            TextInput::make('name')->required(),
            TextInput::make('license_number'),
            TextInput::make('phone'),
            TextInput::make('service_area'),
            TagsInput::make('specialties'),
            Select::make('availability_status')->options([
                'available' => 'Available',
                'on_job' => 'On Job',
                'off' => 'Off Duty',
            ])->required(),
            Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('license_number'),
                TextColumn::make('service_area'),
                TextColumn::make('availability_status')->badge(),
                TextColumn::make('specialties')->badge()->separator(','),
                IconColumn::make('is_active')->boolean(),
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
        return ['index' => ManageTechnicians::route('/')];
    }
}
