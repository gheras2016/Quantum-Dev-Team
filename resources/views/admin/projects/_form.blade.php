@php
    $project ??= null;
    $selectedTech = old('technologies', $project?->technologies->pluck('id')->all() ?? []);
    $selectedCats = old('categories', $project?->categories->pluck('id')->all() ?? []);
    $selectedTags = old('tags', $project?->tags->pluck('id')->all() ?? []);
@endphp

<div class="space-y-5" x-data="slugify()" x-init="touched = $refs.slug.value.length > 0">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="title[ar]" :label="__('messages.title').' ('.__('messages.arabic').')'" :value="$project?->translate('title', 'ar')" required />
        <x-admin.input name="title[en]" :label="__('messages.title').' ('.__('messages.english').')'" :value="$project?->translate('title', 'en')" required x-ref="source" x-on:input="generate()" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="description[ar]" :label="__('messages.description').' ('.__('messages.arabic').')'" :value="$project?->translate('description', 'ar')" required />
        <x-admin.textarea name="description[en]" :label="__('messages.description').' ('.__('messages.english').')'" :value="$project?->translate('description', 'en')" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.input name="slug" :label="__('messages.slug')" :value="$project?->slug" required x-ref="slug" x-on:input="touched = true" />
        <x-admin.input name="client_name" :label="__('messages.client')" :value="$project?->client_name" />
        <x-admin.input name="duration" :label="__('projects.duration')" :value="$project?->duration" />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.select name="status" :label="__('messages.status')" :selected="$project?->status ?? 'draft'"
            :options="['draft' => __('messages.draft'), 'published' => __('messages.published'), 'archived' => __('messages.archived')]" />
        <x-admin.input name="progress" type="number" :label="__('messages.progress').' %'" :value="$project?->progress ?? 0" />
        <div>
            <label class="form-label">{{ __('messages.image') }}</label>
            <input type="file" name="image" accept="image/*" class="form-input">
        </div>
    </div>

    {{-- Gallery: add multiple images (existing images are managed below the form) --}}
    <div>
        <label class="form-label">{{ __('messages.gallery') }}</label>
        <input type="file" name="media[]" accept="image/*" multiple class="form-input">
        @error('media.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="demo_url" type="url" label="Demo URL" :value="$project?->demo_url" />
        <x-admin.input name="github_url" type="url" label="GitHub URL" :value="$project?->github_url" />
        <x-admin.input name="youtube_url" type="url" label="YouTube URL" :value="$project?->youtube_url" />
        <x-admin.input name="documentation_url" type="url" label="Docs URL" :value="$project?->documentation_url" />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        @foreach (['technologies' => [$technologies, $selectedTech], 'categories' => [$categories, $selectedCats], 'tags' => [$tags, $selectedTags]] as $field => [$items, $selected])
            <div>
                <label class="form-label">{{ __('messages.'.($field === 'tags' ? 'tags' : $field)) }}</label>
                <select name="{{ $field }}[]" multiple size="5" class="form-multiselect">
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}" @selected(in_array($item->id, $selected))>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>

    <x-admin.textarea name="case_study" :label="__('projects.case_study')" :value="$project?->case_study" :rows="3" />

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="featured" value="0">
        <input type="checkbox" name="featured" value="1" @checked(old('featured', $project?->featured ?? false)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        {{ __('messages.featured') }}
    </label>

    <details class="rounded-xl border border-gray-100 p-4 dark:border-white/5">
        <summary class="cursor-pointer font-medium text-gray-700 dark:text-gray-300">{{ __('messages.seo_section') }}</summary>
        <div class="mt-4 space-y-4">
            <x-admin.input name="seo_title" :label="__('messages.seo_title')" :value="$project?->seo_title" />
            <x-admin.textarea name="seo_description" :label="__('messages.seo_description')" :value="$project?->seo_description" :rows="2" />
            <x-admin.input name="keywords" :label="__('messages.keywords')" :value="$project?->keywords" />
        </div>
    </details>
</div>
