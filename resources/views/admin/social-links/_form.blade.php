@php $socialLink ??= null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-admin.input name="platform" :label="__('messages.platform')" :value="$socialLink?->platform" required />
    <x-admin.input name="order" type="number" :label="__('messages.order')" :value="$socialLink?->order ?? 0" />
</div>
<div class="mt-5">
    <x-admin.input name="url" type="url" :label="__('messages.url')" :value="$socialLink?->url" required />
</div>
<div class="mt-5">
    <x-admin.input name="icon" :label="__('messages.icon')" :value="$socialLink?->icon" />
</div>
<label class="mt-5 flex items-center gap-2 text-sm">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $socialLink?->is_active ?? true)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
    {{ __('messages.active') }}
</label>
