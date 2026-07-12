@php
    $user ??= null;
    $userRoles = old('roles', $user?->roles->pluck('name')->all() ?? []);
@endphp

<div class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="name" :label="__('messages.name')" :value="$user?->name" required />
        <x-admin.input name="email" type="email" :label="__('messages.email')" :value="$user?->email" required />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-admin.input name="password" type="password" :label="__('messages.password')" :required="! $user" />
        <x-admin.input name="password_confirmation" type="password" :label="__('messages.confirm_password')" :required="! $user" />
    </div>
    @if ($user)
        <p class="-mt-3 text-xs text-gray-400">{{ __('messages.leave_blank_password') }}</p>
    @endif

    <div>
        <label class="form-label">{{ __('messages.roles') }}</label>
        <div class="grid gap-2 sm:grid-cols-2">
            @foreach ($roles as $role)
                <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-white/10">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, $userRoles)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    {{ $role->name }}
                </label>
            @endforeach
        </div>
        @error('roles')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
