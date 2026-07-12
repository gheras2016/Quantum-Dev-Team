@props(['name', 'label' => null, 'value' => null, 'type' => 'text', 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif
    <input type="{{ $type }}"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           @if($required) required @endif
           {{ $attributes->merge(['class' => 'form-input' . ($errors->has($name) ? ' border-red-500' : '')]) }}>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
