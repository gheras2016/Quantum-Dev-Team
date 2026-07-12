@props(['headers' => []])

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-dark-100 dark:ring-white/5">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-100 bg-gray-50 text-start text-xs uppercase tracking-wide text-gray-500 dark:border-white/5 dark:bg-white/5">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-3 text-start font-medium">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
