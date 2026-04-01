<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\Tag;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
#[Title('Dashboard - Bookmark Manager')]
class Dashboard extends Component
{
    public $search = '';
    public $selectedTags = [];
    public $sortBy = 'recently_added';
    public $showArchived = false;
    public $showBookmarkForm = false;
    public $editingBookmark = null;

    #[On('bookmark-created')]
    #[On('bookmark-updated')]
    #[On('bookmark-deleted')]
    public function refreshBookmarks()
    {
        // This method triggers a re-render
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
        } else {
            $this->selectedTags[] = $tagId;
        }
    }

    public function resetFilters()
    {
        $this->selectedTags = [];
        $this->search = '';
        $this->showArchived = false;
    }

    public function openBookmarkForm($bookmarkId = null)
    {
        $this->editingBookmark = $bookmarkId;
        $this->showBookmarkForm = true;
        $this->dispatch('open-dialog');
    }

    public function closeBookmarkForm()
    {
        $this->showBookmarkForm = false;
        $this->editingBookmark = null;
        $this->dispatch('close-dialog');
    }

    public function getBookmarksProperty()
    {
        $query = Bookmark::with('tags')
            ->where('user_id', auth()->id())
            ->where('is_archived', $this->showArchived);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('tags.id', $this->selectedTags);
            });
        }

        switch ($this->sortBy) {
            case 'recently_visited':
                $query->orderBy('last_visited_at', 'desc');
                break;
            case 'most_visited':
                $query->orderBy('view_count', 'desc');
                break;
            default: // recently_added
                $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'bookmarks' => $this->bookmarks,
            'tags' => Tag::withCount('bookmarks')->get(),
        ]);
    }
}
