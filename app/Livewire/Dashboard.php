<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
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
        $tagId = (int) $tagId;
        $selected = array_map('intval', $this->selectedTags);

        if (in_array($tagId, $selected, true)) {
            $this->selectedTags = array_values(array_filter(
                $selected,
                fn(int $id) => $id !== $tagId
            ));
        } else {
            $selected[] = $tagId;
            $this->selectedTags = array_values(array_unique($selected));
        }
    }

    public function resetFilters()
    {
        $this->selectedTags = [];
        $this->search = '';
        $this->showArchived = false;
    }

    public function searchByTitle(): void
    {
        $this->search = trim($this->search);
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

    public function render()
    {
        $userId = Auth::id();

        if (!$userId) {
            return view('livewire.dashboard', [
                'bookmarks' => collect(),
                'tags' => collect(),
            ]);
        }

        $selectedTagIds = array_map('intval', $this->selectedTags);

        $bookmarkQuery = Bookmark::with('tags')
            ->where('user_id', $userId)
            ->where('is_archived', $this->showArchived);

        if ($this->search) {
            $bookmarkQuery->where('title', 'like', trim($this->search) . '%');
        }

        if (!empty($selectedTagIds)) {
            foreach ($selectedTagIds as $tagId) {
                $bookmarkQuery->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            }
        }

        switch ($this->sortBy) {
            case 'recently_visited':
                $bookmarkQuery->orderBy('last_visited_at', 'desc');
                break;
            case 'most_visited':
                $bookmarkQuery->orderBy('view_count', 'desc');
                break;
            default:
                $bookmarkQuery->orderBy('created_at', 'desc');
        }

        return view('livewire.dashboard', [
            'bookmarks' => $bookmarkQuery->get(),
            'tags' => Tag::whereHas('bookmarks', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->withCount(['bookmarks' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])->get(),
        ]);
    }
}
