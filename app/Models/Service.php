<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasLocalizedContent;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'icon',
        'image',
        'order',
        'is_active',
        'status',
        'seo_title',
        'seo_description',
        'keywords',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true)->where('status', 'published');
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order')->orderByDesc('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? media_url($this->image) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
