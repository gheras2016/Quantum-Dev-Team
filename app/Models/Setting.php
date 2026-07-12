<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'value' => 'array',
        'is_public' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    /**
     * All settings as a key => value map (cached).
     */
    public static function all($columns = ['*']): mixed
    {
        return Cache::rememberForever('settings.all', fn () => parent::query()->pluck('value', 'key'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all()->get($key, $default);
    }

    public static function set(string $key, mixed $value, array $attributes = []): void
    {
        static::updateOrCreate(['key' => $key], array_merge(['value' => $value], $attributes));
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('is_public', true);
    }

    public function scopeGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }
}
