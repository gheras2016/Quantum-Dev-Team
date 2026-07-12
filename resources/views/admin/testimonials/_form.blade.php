@php $testimonial ??= null; @endphp

<div class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.input name="author_name" :label="__('messages.author')" :value="$testimonial?->author_name" required />
        <x-admin.input name="author_title" :label="__('messages.author_title')" :value="$testimonial?->author_title" />
        <x-admin.input name="author_company" :label="__('messages.author_company')" :value="$testimonial?->author_company" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="content[ar]" :label="__('messages.message').' ('.__('messages.arabic').')'" :value="$testimonial?->translate('content', 'ar')" required />
        <x-admin.textarea name="content[en]" :label="__('messages.message').' ('.__('messages.english').')'" :value="$testimonial?->translate('content', 'en')" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <x-admin.select name="rating" :label="__('messages.rating')" :selected="$testimonial?->rating ?? 5"
            :options="[5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★']" />
        <x-admin.input name="order" type="number" :label="__('messages.order')" :value="$testimonial?->order ?? 0" />
        <div>
            <label class="form-label">{{ __('messages.avatar') }}</label>
            <input type="file" name="avatar" accept="image/*" class="form-input">
        </div>
    </div>

    @if ($testimonial?->avatar)
        <img src="{{ $testimonial->avatar_url }}" class="h-16 w-16 rounded-full object-cover" alt="">
    @endif

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial?->is_active ?? true)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        {{ __('messages.active') }}
    </label>
</div>
