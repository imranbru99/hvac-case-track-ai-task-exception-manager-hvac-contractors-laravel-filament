<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Local equivalent of imrandevbd/drutogoti — lightweight cache/speed helpers.
 */
class DrutogotiSpeed
{
    public function __construct(protected ModuleManager $modules) {}

    public function remember(string $key, int $seconds, callable $callback): mixed
    {
        if (! $this->modules->enabled('drutogoti_speed')) {
            return $callback();
        }

        return Cache::remember('drutogoti:'.$key, $seconds, $callback);
    }
}
