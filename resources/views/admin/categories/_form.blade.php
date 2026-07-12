@php $category ??= null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-admin.input name="name" :label="__('messages.name')" :value="$category?->name" required />
    <x-admin.input name="slug" :label="__('messages.slug')" :value="$category?->slug" />
</div>
<div class="mt-5">
    <x-admin.input name="type" :label="__('messages.type')" :value="$category?->type" />
</div>
<div class="mt-5">
    <x-admin.textarea name="description" :label="__('messages.description')" :value="$category?->description" />
</div>
