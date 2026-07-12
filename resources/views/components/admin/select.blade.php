@props(['name', 'label' => null, 'options' => [], 'selected' => null, 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" @if($required) required @endif
            {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' border-red-500' : '')]) }}>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected((string) old($name, $selected) === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
