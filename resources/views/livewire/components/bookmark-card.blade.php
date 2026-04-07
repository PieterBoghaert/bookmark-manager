<div class="bg-white rounded-[10px] shadow-md hover:shadow-lg transition-shadow duration-200 flex flex-col">
    <div class="card-content p-4 flex flex-col gap-4 flex-1">
        <div class="flex items-start justify-between gap-4">
            <header class="flex items-start gap-3">
                @if($bookmark->favicon)
                <img src="{{ $bookmark->favicon }}" alt="{{ $bookmark->title }} favicon" class="w-11 h-11">
                @else
                <div class="w-11 h-11"></div>
                @endif
                <div class="min-w-0">
                    <h2>
                        {{ $bookmark->title }}
                    </h2>
                    <p>
                        {{ $bookmark->url }}
                    </p>
                </div>
            </header>

            <div>
                <button
                    type="button"
                    popovertarget="bookmark-actions-{{ $bookmark->id }}"
                    class="bookmark-actions-trigger list-none cursor-pointer rounded-md p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300"
                    aria-label="Open bookmark actions">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10 3a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm0 5.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm0 5.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                    </svg>
                </button>
                <div
                    id="bookmark-actions-{{ $bookmark->id }}"
                    popover
                    class="bookmark-actions-menu z-10 w-48 rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <a
                        href="{{ $bookmark->url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        wire:click="visitBookmark"
                        class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        Visit
                    </a>
                    <button
                        type="button"
                        wire:click="copyUrl"
                        class="block w-full px-3 py-2 text-left text-sm text-gray-700 bg-white hover:bg-gray-100">
                        Copy URL
                    </button>
                    @if(!$bookmark->is_archived)
                    <button
                        type="button"
                        wire:click="togglePin"
                        class="block w-full px-3 py-2 text-left text-sm text-gray-700 bg-white hover:bg-gray-100 dark:text-gray-200 ">
                        Unpin
                    </button>
                    <button
                        type="button"
                        wire:click="editBookmark({{ $bookmark->id }})"
                        class="block w-full px-3 py-2 text-left text-sm text-gray-700 bg-white hover:bg-gray-100 ">
                        Edit
                    </button>
                    <button
                        type="button"
                        wire:click="toggleArchive"
                        class="block w-full px-3 py-2 text-left text-sm text-gray-700 bg-white hover:bg-gray-100">
                        Archive
                    </button>
                    @else
                    <button
                        type="button"
                        wire:click="toggleArchive"
                        class="block w-full px-3 py-2 text-left text-sm text-gray-700 bg-white hover:bg-gray-100">
                        Unarchive
                    </button>
                    <button
                        type="button"
                        wire:click="deleteBookmark"
                        wire:confirm="Are you sure you want to delete this bookmark permanently?"
                        class="block w-full px-3 py-2 text-left text-sm text-red-600 bg-white hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                        Delete Permanently
                    </button>
                    @endif
                </div>
            </div>
        </div>


        <p class="text-sm text-gray-700 pt-4 border-t border-gray-200">
            {{ $bookmark->description ?: 'No description provided.' }}
        </p>


        <div class="flex flex-wrap gap-2">
            @foreach($bookmark->tags as $tag)
            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">
                {{ $tag->name }}
            </span>
            @endforeach
        </div>
    </div>
    <footer class="py-3 px-4 border-t border-gray-200 pt-3 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400 flex items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
            <span class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="8" viewBox="0 0 11 8" fill="none" class="text-gray-500 dark:text-gray-400" aria-hidden="true">
                    <path d="M0.631936 4.35659C0.563843 4.24877 0.529796 4.19486 0.510737 4.11171C0.496421 4.04925 0.496421 3.95075 0.510737 3.88829C0.529796 3.80514 0.563843 3.75123 0.631936 3.64341C1.19464 2.75242 2.86957 0.5 5.42208 0.5C7.97458 0.5 9.64951 2.75242 10.2122 3.64341C10.2803 3.75123 10.3144 3.80514 10.3334 3.88829C10.3477 3.95075 10.3477 4.04925 10.3334 4.11171C10.3144 4.19486 10.2803 4.24877 10.2122 4.35659C9.64951 5.24758 7.97458 7.5 5.42208 7.5C2.86958 7.5 1.19464 5.24758 0.631936 4.35659Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M5.42208 5.5C6.2505 5.5 6.92208 4.82843 6.92208 4C6.92208 3.17157 6.2505 2.5 5.42208 2.5C4.59365 2.5 3.92208 3.17157 3.92208 4C3.92208 4.82843 4.59365 5.5 5.42208 5.5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ $bookmark->view_count }}
            </span>
            <span class="inline-flex items-center gap-1">
                <svg xmlns=" http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M6 3V6L8 7M11 6C11 8.76142 8.76142 11 6 11C3.23858 11 1 8.76142 1 6C1 3.23858 3.23858 1 6 1C8.76142 1 11 3.23858 11 6Z" stroke="#4C5C59" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ $bookmark->created_at?->format('d M') }}
            </span>
            <span class="inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M10.5 5H1.5M8 1V3M4 1V3M3.9 11H8.1C8.94008 11 9.36012 11 9.68099 10.8365C9.96323 10.6927 10.1927 10.4632 10.3365 10.181C10.5 9.86012 10.5 9.44008 10.5 8.6V4.4C10.5 3.55992 10.5 3.13988 10.3365 2.81901C10.1927 2.53677 9.96323 2.3073 9.68099 2.16349C9.36012 2 8.94008 2 8.1 2H3.9C3.05992 2 2.63988 2 2.31901 2.16349C2.03677 2.3073 1.8073 2.53677 1.66349 2.81901C1.5 3.13988 1.5 3.55992 1.5 4.4V8.6C1.5 9.44008 1.5 9.86012 1.66349 10.181C1.8073 10.4632 2.03677 10.6927 2.31901 10.8365C2.63988 11 3.05992 11 3.9 11Z" stroke="#4C5C59" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ $bookmark->last_visited_at?->format('d M') ?? 'Never' }}
            </span>
        </div>
        <div>
            @if($bookmark->is_archived)
            <span class=" inline-flex items-center rounded-full bg-amber-100 px-2 py-1 text-[11px] font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                Archived
            </span>
            @endif
        </div>
    </footer>
</div>

@script
<script>
    $wire.on('url-copied', (event) => {
        navigator.clipboard.writeText(event.url);
        alert('URL copied to clipboard!');
    });
</script>
@endscript