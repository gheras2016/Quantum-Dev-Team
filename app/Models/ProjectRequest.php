<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectRequest extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = ['pending', 'in_review', 'approved', 'in_progress', 'completed', 'rejected'];

    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'project_type',
        'budget_range',
        'description',
        'status',
        'is_read',
        'notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->forceFill(['is_read' => true])->save();
        }
    }
}
