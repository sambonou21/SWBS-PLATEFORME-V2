<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, $value, string $group = 'general', string $type = 'string'): Setting
    {
        return Setting::set($key, $value, $group, $type);
    }

    public function allByGroup(string $group): array
    {
        return Setting::where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }
}