<?php

namespace App\Services;

use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    public function enabled(string $key): bool
    {
        return (bool) ($this->all()[$key]['enabled'] ?? false);
    }

    public function all(): array
    {
        return Cache::remember('module_settings_map', 60, function () {
            $fromDb = ModuleSetting::query()->get()->keyBy('key');
            $defs = config('modules.definitions', []);
            $map = [];

            foreach ($defs as $key => $def) {
                $row = $fromDb->get($key);
                $map[$key] = [
                    'key' => $key,
                    'name' => $row?->name ?? $def['name'],
                    'description' => $row?->description ?? $def['description'],
                    'category' => $row?->category ?? $def['category'],
                    'enabled' => $row ? (bool) $row->enabled : (bool) $def['enabled'],
                ];
            }

            return $map;
        });
    }

    public function syncFromConfig(): void
    {
        foreach (config('modules.definitions', []) as $key => $def) {
            ModuleSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $def['name'],
                    'description' => $def['description'],
                    'category' => $def['category'],
                    'enabled' => $def['enabled'],
                ]
            );
        }

        Cache::forget('module_settings_map');
    }

    public function forgetCache(): void
    {
        Cache::forget('module_settings_map');
    }
}
