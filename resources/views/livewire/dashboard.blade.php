<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 min-h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-8">
                <button wire:click="resetFilters" class="text-2xl font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors cursor-pointer">📚 Bookmarks</button>
            </div>

            <!-- Navigation -->
            <nav class="mb-6 space-y-1">
                <button
                    wire:click="resetFilters"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ !$showArchived ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🏠 Home
                </button>
                <button
                    wire:click="$set('showArchived', true)"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $showArchived ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🗄️ Archived
                </button>
            </nav>

            <!-- Tags Filter -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center justify-between">
                    <span>Tags</span>
                    @if(count($selectedTags) > 0)
                    <button wire:click="resetFilters" class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                        Reset
                    </button>
                    @endif
                </h3>
                <div class="space-y-2">
                    @foreach($tags as $tag)
                    <label class="flex items-center cursor-pointer group">
                        <input
                            type="checkbox"
                            wire:change="toggleTag({{ $tag->id }})"
                            @checked(in_array((int) $tag->id, array_map('intval', $selectedTags), true))
                        class="rounded text-teal-700 accent-teal-700 focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">
                            {{ $tag->name }} ({{ $tag->bookmarks_count }})
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">

                    <div class="flex-1 max-w-2xl">
                        <input
                            type="text"
                            wire:model.live.debounce.1000ms="search"
                            wire:keydown.enter.prevent="searchByTitle"
                            placeholder="Search bookmarks..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex items-center space-x-4 ml-6">


                        <!-- Dark Mode Toggle -->
                        <button
                            x-data
                            @click="darkMode = !darkMode"
                            class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        <!-- Add Bookmark Button -->
                        <button
                            wire:click="openBookmarkForm"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200 cursor-pointer">
                            + Add Bookmark
                        </button>

                    </div>
                </div>
            </header>


            <h1 class="px-6 pt-6 pb-2 text-xl font-semibold text-gray-800 dark:text-white">
                @if($search && count($selectedTags) > 0)
                Results for: "{{ $search }}" &middot; Tagged: {{ $selectedTagNames->join(', ') }}
                @elseif($search)
                Results for: "{{ $search }}"
                @elseif(count($selectedTags) > 0)
                Bookmarks tagged: {{ $selectedTagNames->join(', ') }}
                @else
                All bookmarks
                @endif
            </h1>
            <!-- Bookmarks Grid -->
            <div class="p-6 bookmarks-container" style="view-transition-name: bookmarks-container;">
                @if($bookmarks->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">
                        No bookmarks found. Create your first bookmark!
                    </p>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:key="bookmarks-grid-{{ md5(json_encode([$selectedTags, $search, $showArchived])) }}">
                    @foreach($bookmarks as $bookmark)
                    <livewire:components.bookmark-card :bookmark="$bookmark" :key="'bookmark-' . $bookmark->id . '-' . md5(json_encode([$selectedTags, $search, $showArchived]))" />
                    @endforeach
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Bookmark Form Dialog -->
    <dialog
        id="bookmarkDialog"
        class="bookmark-dialog bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full p-0 border-0">
        <div class="p-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $editingBookmark ? 'Edit Bookmark' : 'Add New Bookmark' }}
                </h1>
                <h4 class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $editingBookmark ? 'Update your bookmark details below' : 'Save your favorite websites and organize them with tags' }}
                </h4>
            </div>

            <livewire:components.bookmark-form :bookmarkId="$editingBookmark" :key="$editingBookmark ?? 'new'" />
        </div>
    </dialog>
</div>

@script
<script>
    const dialog = document.getElementById('bookmarkDialog');

    // Listen for open dialog event
    window.addEventListener('open-dialog', () => {
        dialog?.showModal();
    });

    // Listen for close dialog event
    window.addEventListener('close-dialog', () => {
        dialog?.close();
    });

    // Also listen for close-modal event from form
    window.addEventListener('close-modal', () => {
        dialog?.close();
        $wire.call('closeBookmarkForm');
    });

    // Close on backdrop click
    dialog?.addEventListener('click', (e) => {
        const rect = dialog.getBoundingClientRect();
        if (
            e.clientX < rect.left ||
            e.clientX > rect.right ||
            e.clientY < rect.top ||
            e.clientY > rect.bottom
        ) {
            $wire.call('closeBookmarkForm');
        }
    });
</script>
@endscript