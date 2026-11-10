<?php

namespace App\Filament\Resources\AuditLogs;

use App\Filament\Resources\AuditLogs\Pages\ManageAuditLogs;
use App\Models\AuditLog;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Compliance';

    protected static ?int $navigationSort = 80;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('audit_log');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('action')->badge()->searchable(),
                TextColumn::make('user.name')->label('User')->placeholder('System'),
                TextColumn::make('entity_type')->label('Entity'),
                TextColumn::make('entity_id')->label('ID'),
                TextColumn::make('ip_address'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAuditLogs::route('/')];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
