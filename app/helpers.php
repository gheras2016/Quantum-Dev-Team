<?php

use App\Models\Setting;

if (! function_exists('media_url')) {
    /**
     * Resolve a stored media reference to a URL. Full URLs (e.g. Cloudinary)
     * are returned as-is; local paths are served from the public disk.
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : asset('storage/'.$path);
    }
}

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
