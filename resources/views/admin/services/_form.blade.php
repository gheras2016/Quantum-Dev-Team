@php $service ??= null; @endphp

<div class="space-y-5" x-data="slugify()" x-init="touched = $refs.slug.value.length > 0">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="title[ar]" :label="__('messages.title').' ('.__('messages.arabic').')'" :value="$service?->translate('title', 'ar')" required />
        <x-admin.input name="title[en]" :label="__('messages.title').' ('.__('messages.english').')'" :value="$service?->translate('title', 'en')" required x-ref="source" x-on:input="generate()" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="description[ar]" :label="__('messages.description').' ('.__('messages.arabic').')'" :value="$service?->translate('description', 'ar')" required />
        <x-admin.textarea name="description[en]" :label="__('messages.description').' ('.__('messages.english').')'" :value="$service?->translate('description', 'en')" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.input name="slug" :label="__('messages.slug')" :value="$service?->slug" required x-ref="slug" x-on:input="touched = true" />
        <x-admin.input name="order" type="number" :label="__('messages.order')" :value="$service?->order ?? 0" />
        <x-admin.select name="status" :label="__('messages.status')" :selected="$service?->status ?? 'published'"
            :options="['draft' => __('messages.draft'), 'published' => __('messages.published'), 'archived' => __('messages.archived')]" />
    </div>

    <x-admin.input name="icon" :label="__('messages.icon').' (SVG path)'" :value="$service?->icon" />

    <div>
        <label class="form-label">{{ __('messages.image') }}</label>
        @if ($service?->image)
            <img src="{{ $service->image_url }}" class="mb-2 h-20 rounded-lg object-cover" alt="">
        @endif
        <input type="file" name="image" accept="image/*" class="form-input">
        @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        {{ __('messages.active') }}
    </label>

    <details class="rounded-xl border border-gray-100 p-4 dark:border-white/5">
        <summary class="cursor-pointer font-medium text-gray-700 dark:text-gray-300">{{ __('messages.seo_section') }}</summary>
        <div class="mt-4 space-y-4">
            <x-admin.input name="seo_title" :label="__('messages.seo_title')" :value="$service?->seo_title" />
            <x-admin.textarea name="seo_description" :label="__('messages.seo_description')" :value="$service?->seo_description" :rows="2" />
            <x-admin.input name="keywords" :label="__('messages.keywords')" :value="$service?->keywords" />
        </div>
    </details>
</div>
