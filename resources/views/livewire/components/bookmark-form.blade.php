<form wire:submit="save" class="space-y-6">
    <!-- Title -->
    <div>
        <label for="title" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Title
        </label>
        <input 
            type="text" 
            id="title" 
            wire:model="title"
            placeholder="e.g., Laravel Documentation"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all"
            required
        >
        @error('title') 
            <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p> 
        @enderror
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Description
        </label>
        <textarea 
            id="description" 
            wire:model="description"
            rows="3"
            placeholder="Brief description of this bookmark (optional)"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all resize-none"
        ></textarea>
        @error('description') 
            <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p> 
        @enderror
    </div>

    <!-- Website URL -->
    <div>
        <label for="url" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Website URL
        </label>
        <div class="relative">
            @if($favicon)
                <div class="absolute left-3 top-1/2 -translate-y-1/2">
                    <img src="{{ $favicon }}" alt="Favicon" class="w-5 h-5 rounded">
                </div>
            @endif
            <input 
                type="url" 
                id="url" 
                wire:model="url"
                wire:blur="fetchFavicon"
                placeholder="https://example.com"
                class="w-full {{ $favicon ? 'pl-11' : 'pl-4' }} pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-all"
                required
            >
        </div>
        @error('url') 
            <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p> 
        @enderror
    </div>

    <!-- Tags -->
    <div>
        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            Tags
        </label>
        
        <!-- Existing Tags -->
        @if($allTags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                @foreach($allTags as $tag)
                    <label class="inline-flex items-center px-3 py-1.5 rounded-full cursor-pointer transition-all {{ in_array($tag->id, $selectedTags) ? 'bg-indigo-100 dark:bg-indigo-900 ring-2 ring-indigo-500' : 'bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                        <input 
                            type="checkbox" 
                            wire:model="selectedTags"
                            value="{{ $tag->id }}"
                            class="sr-only"
                        >
                        <span class="text-sm font-medium {{ in_array($tag->id, $selectedTags) ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $tag->name }}
                        </span>
                    </label>
                @endforeach
            </div>
        @endif

        <!-- Add New Tag -->
        <div class="flex items-center gap-2">
            <input 
                type="text" 
                wire:model="newTag"
                wire:keydown.enter.prevent="addNewTag"
                placeholder="Create a new tag..."
                class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm transition-all"
            >
            <button 
                type="button"
                wire:click="addNewTag"
                class="px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-xl transition-all"
            >
                + Add
            </button>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button 
            type="button"
            wire:click="$dispatch('close-modal')"
            class="px-6 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-xl transition-all"
        >
            Cancel
        </button>
        <button 
            type="submit"
            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40"
        >
            {{ $bookmarkId ? '✓ Update Bookmark' : '+ Add Bookmark' }}
        </button>
    </div>
</form>
