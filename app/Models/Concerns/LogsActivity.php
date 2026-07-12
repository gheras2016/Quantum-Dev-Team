<?php

namespace App\Models\Concerns;

use App\Services\ActivityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Automatically records create / update / delete activity for a model
 * through the central ActivityService, so individual services no longer
 * repeat logging boilerplate.
 *
 * Logging only happens when there is an authenticated actor, so database
 * seeders and system tasks never pollute the audit trail — it stays limited
 * to real, attributable user actions.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $model) => $model->recordActivity('created'));
        static::updated(fn (Model $model) => $model->recordActivity('updated'));
        static::deleted(fn (Model $model) => $model->recordActivity('deleted'));
    }

    public function recordActivity(string $event): void
    {
        if (! Auth::check()) {
            return;
        }

        app(ActivityService::class)->log(
            description: sprintf('%s %s: %s', ucfirst($event), class_basename($this), $this->activityLabel()),
            subject: $this,
            logName: $this->getTable(),
        );
    }

    /**
     * A human friendly label for the model in the activity feed.
     * Models may override this.
     */
    public function activityLabel(): string
    {
        foreach (['name', 'slug', 'title', 'email', 'platform'] as $key) {
            $value = $this->getAttribute($key);

            if (is_string($value) && $value !== '') {
                return $value;
            }

            if (is_array($value)) {
                return $value[app()->getLocale()] ?? reset($value) ?: (string) $this->getKey();
            }
        }

        return '#'.$this->getKey();
    }
}
