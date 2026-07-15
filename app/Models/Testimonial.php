<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedContent;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasLocalizedContent;

    protected $fillable = [
        'author_name',
        'author_title',
        'author_company',
        'avatar',
        'content',
        'rating',
        'order',
        'is_active',
    ];

    protected $casts = [
        'content' => 'array',
        'rating' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order')->orderByDesc('id');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? media_url($this->avatar) : null;
    }

    public function activityLabel(): string
    {
        return $this->author_name;
    }
}
