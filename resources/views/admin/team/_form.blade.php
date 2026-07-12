@php $member ??= null; @endphp

<div class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="name" :label="__('messages.name')" :value="$member?->name" required />
        <x-admin.input name="role" :label="__('messages.role')" :value="$member?->role" required />
    </div>

    <x-admin.textarea name="bio" :label="__('messages.description')" :value="$member?->bio" :rows="3" />

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="email" type="email" :label="__('messages.email')" :value="$member?->email" />
        <x-admin.input name="phone" :label="__('messages.phone')" :value="$member?->phone" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="linkedin_url" type="url" label="LinkedIn URL" :value="$member?->linkedin_url" />
        <x-admin.input name="github_url" type="url" label="GitHub URL" :value="$member?->github_url" />
    </div>

    <x-admin.input name="skills" :label="__('messages.skills')" :value="$member ? implode(', ', (array) $member->skills) : ''" :placeholder="__('messages.skills_hint')" />

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.input name="order" type="number" :label="__('messages.order')" :value="$member?->order ?? 0" />
        <x-admin.select name="status" :label="__('messages.status')" :selected="$member?->status ?? 'published'"
            :options="['draft' => __('messages.draft'), 'published' => __('messages.published'), 'archived' => __('messages.archived')]" />
        <div>
            <label class="form-label">{{ __('messages.image') }}</label>
            <input type="file" name="image" accept="image/*" class="form-input">
        </div>
    </div>

    @if ($member?->image)
        <img src="{{ $member->image_url }}" class="h-20 w-20 rounded-full object-cover" alt="">
    @endif

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $member?->is_active ?? true)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        {{ __('messages.active') }}
    </label>
</div>
