@php
    $post ??= null;
    $selectedTags = old('tags', $post?->tags->pluck('id')->all() ?? []);
@endphp

<div class="space-y-5" x-data="slugify()" x-init="touched = $refs.slug.value.length > 0">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="title[ar]" :label="__('messages.title').' ('.__('messages.arabic').')'" :value="$post?->translate('title', 'ar')" required />
        <x-admin.input name="title[en]" :label="__('messages.title').' ('.__('messages.english').')'" :value="$post?->translate('title', 'en')" required x-ref="source" x-on:input="generate()" />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.input name="slug" :label="__('messages.slug')" :value="$post?->slug" required x-ref="slug" x-on:input="touched = true" />
        <x-admin.select name="status" :label="__('messages.status')" :selected="$post?->status ?? 'draft'"
            :options="['draft' => __('messages.draft'), 'published' => __('messages.published'), 'archived' => __('messages.archived')]" />
        <div>
            <label class="form-label">{{ __('messages.image') }}</label>
            <input type="file" name="image" accept="image/*" class="form-input">
        </div>
    </div>

    @if ($post?->image)
        <img src="{{ $post->image_url }}" class="h-24 rounded-lg object-cover" alt="">
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="excerpt[ar]" :label="__('messages.excerpt').' ('.__('messages.arabic').')'" :value="$post?->translate('excerpt', 'ar')" :rows="2" />
        <x-admin.textarea name="excerpt[en]" :label="__('messages.excerpt').' ('.__('messages.english').')'" :value="$post?->translate('excerpt', 'en')" :rows="2" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="body[ar]" :label="__('messages.body').' ('.__('messages.arabic').')'" :value="$post?->translate('body', 'ar')" :rows="8" required />
        <x-admin.textarea name="body[en]" :label="__('messages.body').' ('.__('messages.english').')'" :value="$post?->translate('body', 'en')" :rows="8" required />
    </div>

    <div>
        <label class="form-label">{{ __('messages.tags') }}</label>
        <select name="tags[]" multiple size="4" class="form-multiselect">
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags))>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="featured" value="0">
        <input type="checkbox" name="featured" value="1" @checked(old('featured', $post?->featured ?? false)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        {{ __('messages.featured') }}
    </label>

    <details class="rounded-xl border border-gray-100 p-4 dark:border-white/5">
        <summary class="cursor-pointer font-medium text-gray-700 dark:text-gray-300">{{ __('messages.seo_section') }}</summary>
        <div class="mt-4 space-y-4">
            <x-admin.input name="seo_title" :label="__('messages.seo_title')" :value="$post?->seo_title" />
            <x-admin.textarea name="seo_description" :label="__('messages.seo_description')" :value="$post?->seo_description" :rows="2" />
            <x-admin.input name="keywords" :label="__('messages.keywords')" :value="$post?->keywords" />
        </div>
    </details>
</div>
