@php $faq ??= null; @endphp

<div class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="question[ar]" :label="__('messages.question').' ('.__('messages.arabic').')'" :value="$faq?->translate('question', 'ar')" required />
        <x-admin.input name="question[en]" :label="__('messages.question').' ('.__('messages.english').')'" :value="$faq?->translate('question', 'en')" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.textarea name="answer[ar]" :label="__('messages.answer').' ('.__('messages.arabic').')'" :value="$faq?->translate('answer', 'ar')" :rows="4" required />
        <x-admin.textarea name="answer[en]" :label="__('messages.answer').' ('.__('messages.english').')'" :value="$faq?->translate('answer', 'en')" :rows="4" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="order" type="number" :label="__('messages.order')" :value="$faq?->order ?? 0" />
        <label class="flex items-end gap-2 pb-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq?->is_active ?? true)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            {{ __('messages.active') }}
        </label>
    </div>
</div>
