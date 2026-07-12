<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Read a site setting by key with a fallback default.
     * Values are cached in the Setting model.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::get($key, $default);

        return ($value === null || $value === '') ? $default : $value;
    }
}
