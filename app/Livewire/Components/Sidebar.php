<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public $tags;
    public $selectedTags;

    public function mount($tags, $selectedTags)
    {
        $this->tags = $tags;
        $this->selectedTags = $selectedTags;
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
