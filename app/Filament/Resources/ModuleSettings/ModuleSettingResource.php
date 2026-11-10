<?php

namespace App\Filament\Resources\ModuleSettings;

use App\Filament\Resources\ModuleSettings\Pages\ManageModuleSettings;
use App\Models\ModuleSetting;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ModuleSettingResource extends Resource
{
    protected static ?string $model = ModuleSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 90;

    protected static ?string $navigationLabel = 'Feature Modules';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->disabled(),
            TextInput::make('name')->required(),
            TextInput::make('category')->required(),
            Textarea::make('description')->columnSpanFull(),
            Toggle::make('enabled')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('key')->toggleable(),
                TextColumn::make('category')->badge(),
                IconColumn::make('enabled')->boolean(),
                TextColumn::make('description')->limit(60),
            ])
            ->defaultSort('category')
            ->recordActions([
                EditAction::make()->after(fn () => app(ModuleManager::class)->forgetCache()),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageModuleSettings::route('/')];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
