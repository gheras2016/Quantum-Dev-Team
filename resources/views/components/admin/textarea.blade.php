@props(['name', 'label' => null, 'value' => null, 'rows' => 4, 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif
    <textarea id="{{ $name }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              @if($required) required @endif
              {{ $attributes->merge(['class' => 'form-textarea' . ($errors->has($name) ? ' border-red-500' : '')]) }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
