<div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
    <h4 class="font-medium text-amber-800 dark:text-amber-300">{{ __('two-factor.recovery_title') }}</h4>
    <p class="mt-1 text-sm text-amber-700 dark:text-amber-300/80">{{ __('two-factor.recovery_intro') }}</p>
    <div class="mt-3 grid grid-cols-2 gap-2 font-mono text-sm">
        @foreach ($recoveryCodes as $code)
            <code class="rounded bg-white px-2 py-1 text-gray-800 dark:bg-dark-200 dark:text-gray-100">{{ $code }}</code>
        @endforeach
    </div>
</div>
