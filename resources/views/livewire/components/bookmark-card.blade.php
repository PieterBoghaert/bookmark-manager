<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                {{ $bookmark->title }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                {{ $bookmark->url }}
            </p>
        </div>

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
                    class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    Copy URL
                </button>
                @if(!$bookmark->is_archived)
                <button
                    type="button"
                    wire:click="togglePin"
                    class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    Unpin
                </button>
                <button
                    type="button"
                    wire:click="editBookmark({{ $bookmark->id }})"
                    class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    Edit
                </button>
                <button
                    type="button"
                    wire:click="toggleArchive"
                    class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    Archive
                </button>
                @else
                <button
                    type="button"
                    wire:click="toggleArchive"
                    class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                    Unarchive
                </button>
                <button
                    type="button"
                    wire:click="deleteBookmark"
                    wire:confirm="Are you sure you want to delete this bookmark permanently?"
                    class="block w-full px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                    Delete Permanently
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4">
        <p class="text-sm text-gray-700 dark:text-gray-300 min-h-10">
            {{ $bookmark->description ?: 'No description provided.' }}
        </p>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($bookmark->tags as $tag)
        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">
            {{ $tag->name }}
        </span>
        @endforeach
    </div>

    <div class="mt-4 border-t border-gray-200 pt-3 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400 flex items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
            <span>Visit count: {{ $bookmark->view_count }}</span>
            <span>Created: {{ $bookmark->created_at?->format('d M Y H:i') }}</span>
            <span>Last visited: {{ $bookmark->last_visited_at?->format('d M Y H:i') ?? 'Never' }}</span>
        </div>
        <div>
            @if($bookmark->is_archived)
            <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-1 text-[11px] font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                Archived
            </span>
            @endif
        </div>
    </div>
</div>

@script
<script>
    $wire.on('url-copied', (event) => {
        navigator.clipboard.writeText(event.url);
        alert('URL copied to clipboard!');
    });
</script>
@endscript