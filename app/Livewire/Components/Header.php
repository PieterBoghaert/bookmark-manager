<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Header extends Component
{
    public $search;
    public $sortBy;

    public function mount($search, $sortBy)
    {
        $this->search = $search;
        $this->sortBy = $sortBy;
    }

    public function render()
    {
        return view('livewire.components.header');
    }
}
