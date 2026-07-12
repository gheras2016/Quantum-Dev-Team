<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'uuid',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeInCollection(Builder $query, string $collection): void
    {
        $query->where('collection_name', $collection);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->file_name ? asset('storage/'.$this->file_name) : null;
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = (int) $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
