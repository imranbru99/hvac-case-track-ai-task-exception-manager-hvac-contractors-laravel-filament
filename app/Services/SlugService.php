<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Local equivalent of imrandevbd/laravel-universal-slug.
 */
class SlugService
{
    public function make(string $value): string
    {
        $slug = Str::slug($value);
        if ($slug === '') {
            $slug = 'item-'.Str::lower(Str::random(6));
        }

        return $slug;
    }

    public function unique(string $value, string $table, string $column = 'slug'): string
    {
        $base = $this->make($value);
        $slug = $base;
        $i = 1;

        while (DB::table($table)->where($column, $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
