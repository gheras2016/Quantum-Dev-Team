@php $technology ??= null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-admin.input name="name" :label="__('messages.name')" :value="$technology?->name" required />
    <x-admin.input name="slug" :label="__('messages.slug')" :value="$technology?->slug" />
</div>
<div class="mt-5">
    <x-admin.input name="icon" :label="__('messages.icon')" :value="$technology?->icon" />
</div>
<div class="mt-5">
    <x-admin.textarea name="description" :label="__('messages.description')" :value="$technology?->description" />
</div>
