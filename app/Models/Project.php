<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasLocalizedContent;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'client_name',
        'project_url',
        'image',
        'duration',
        'progress',
        'case_study',
        'views_count',
        'github_url',
        'demo_url',
        'youtube_url',
        'documentation_url',
        'status',
        'featured',
        'published_at',
        'seo_title',
        'seo_description',
        'keywords',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'progress' => 'integer',
        'views_count' => 'integer',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderByDesc('featured')->latest('published_at')->latest('id');
    }

    public function scopeSearch(Builder $query, ?string $search): void
    {
        if (blank($search)) {
            return;
        }

        $query->where(function (Builder $q) use ($search) {
            $q->where('slug', 'like', "%{$search}%")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->orWhere('title->en', 'like', "%{$search}%")
                ->orWhere('title->ar', 'like', "%{$search}%");
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? media_url($this->image) : null;
    }

    public function getYoutubeVideoIdAttribute(): ?string
    {
        if (blank($this->youtube_url)) {
            return null;
        }

        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $this->youtube_url, $m);

        return $m[1] ?? null;
    }

    public function incrementViews(): void
    {
        $this->incrementQuietly('views_count');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
