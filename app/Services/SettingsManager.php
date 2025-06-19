<?php

namespace App\Services;

use App\Models\Setting;

class SettingsManager
{
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, $value, string $type = 'string'): void
    {
        Setting::set($key, $value, $type);
    }

    public function all(): array
    {
        return Setting::all()->pluck('value', 'key')->toArray();
    }
}
