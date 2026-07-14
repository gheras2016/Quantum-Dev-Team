<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * The editable settings schema: group => [key => input type].
     */
    private array $schema = [
        'general' => [
            'site_email' => 'email',
            'site_phone' => 'text',
            'site_whatsapp' => 'text',
            'site_address_ar' => 'text',
            'site_address_en' => 'text',
        ],
        'hero' => [
            'hero_title_ar' => 'text',
            'hero_title_en' => 'text',
            'hero_subtitle_ar' => 'textarea',
            'hero_subtitle_en' => 'textarea',
        ],
        'stats' => [
            'stat_projects' => 'number',
            'stat_clients' => 'number',
            'stat_years' => 'number',
        ],
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'schema' => $this->schema,
            'values' => Setting::all(),
        ]);
    }

    public function update(Request $request, ActivityService $activity, MediaService $media): RedirectResponse
    {
        $request->validate([
            'site_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg,gif', 'max:2048'],
        ]);

        // Text settings.
        $keys = collect($this->schema)->flatMap(fn ($fields) => array_keys($fields));

        foreach ($keys as $key) {
            Setting::set($key, (string) $request->input($key, ''));
        }

        // Logo: upload a new one, or remove the current one.
        if ($request->boolean('remove_logo')) {
            $media->delete(Setting::get('site_logo'));
            Setting::set('site_logo', '', ['group' => 'branding', 'is_public' => true]);
        } elseif ($request->hasFile('site_logo')) {
            $media->delete(Setting::get('site_logo'));
            Setting::set(
                'site_logo',
                $media->store($request->file('site_logo'), 'branding'),
                ['group' => 'branding', 'is_public' => true]
            );
        }

        $activity->log('Updated site settings');

        return back()->with('success', __('messages.updated_successfully'));
    }
}
