<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Support\DeviceDetector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Single entry point for recording audit-trail activity.
 *
 * Replaces the ~40 duplicated ActivityLog::create([...]) blocks scattered
 * across the legacy controllers and services.
 */
class ActivityService
{
    public function log(string $description, ?Model $subject = null, array $properties = [], ?string $logName = null): ActivityLog
    {
        $causer = Auth::user();

        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'causer_type' => $causer?->getMorphClass(),
            'causer_id' => $causer?->getKey(),
            'properties' => $properties ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'device_type' => DeviceDetector::detect(Request::userAgent()),
        ]);
    }
}
