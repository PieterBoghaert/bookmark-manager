<?php

namespace App\Livewire\Components;

use App\Models\Bookmark;
use Livewire\Component;

class BookmarkCard extends Component
{
    public Bookmark $bookmark;

    public function mount(Bookmark $bookmark)
    {
        $this->bookmark = $bookmark;
    }

    public function visitBookmark()
    {
        $this->bookmark->increment('view_count');
        $this->bookmark->update(['last_visited_at' => now()]);
    }

    public function togglePin()
    {
        $this->bookmark->update(['is_pinned' => !$this->bookmark->is_pinned]);
        $this->dispatch('bookmark-updated');
    }

    public function toggleArchive()
    {
        $this->bookmark->update(['is_archived' => !$this->bookmark->is_archived]);
        $this->dispatch('bookmark-updated');
    }

    public function deleteBookmark()
    {
        $this->bookmark->delete();
        $this->dispatch('bookmark-deleted');
    }

    public function copyUrl()
    {
        $this->dispatch('url-copied', url: $this->bookmark->url);
    }

    public function render()
    {
        return view('livewire.components.bookmark-card');
    }
}
