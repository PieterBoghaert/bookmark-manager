<?php

namespace App\Livewire\Components;

use App\Models\Bookmark;
use App\Models\Tag;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BookmarkForm extends Component
{
    public $bookmarkId = null;
    public $title = '';
    public $description = '';
    public $url = '';
    public $favicon = '';
    public $selectedTags = [];
    public $newTag = '';

    public function mount($bookmarkId = null)
    {
        if ($bookmarkId) {
            $bookmark = Bookmark::with('tags')->findOrFail($bookmarkId);
            $this->bookmarkId = $bookmark->id;
            $this->title = $bookmark->title;
            $this->description = $bookmark->description;
            $this->url = $bookmark->url;
            $this->favicon = $bookmark->favicon;
            $this->selectedTags = $bookmark->tags->pluck('id')->toArray();
        }
    }

    public function fetchFavicon()
    {
        if ($this->url) {
            try {
                $domain = parse_url($this->url, PHP_URL_HOST);
                $this->favicon = "https://www.google.com/s2/favicons?domain={$domain}&sz=64";
            } catch (\Exception $e) {
                $this->favicon = '';
            }
        }
    }

    public function addNewTag()
    {
        if ($this->newTag) {
            $tag = Tag::firstOrCreate(['name' => $this->newTag]);
            $this->selectedTags[] = $tag->id;
            $this->newTag = '';
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'nullable|string',
        ]);

        if (!$this->favicon) {
            $this->fetchFavicon();
        }

        if ($this->bookmarkId) {
            $bookmark = Bookmark::findOrFail($this->bookmarkId);
            $bookmark->update([
                'title' => $this->title,
                'description' => $this->description,
                'url' => $this->url,
                'favicon' => $this->favicon,
            ]);
            $event = 'bookmark-updated';
        } else {
            $bookmark = Bookmark::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'description' => $this->description,
                'url' => $this->url,
                'favicon' => $this->favicon,
            ]);
            $event = 'bookmark-created';
        }

        $bookmark->tags()->sync($this->selectedTags);

        $this->dispatch($event);
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.components.bookmark-form', [
            'allTags' => Tag::all(),
        ]);
    }
}
