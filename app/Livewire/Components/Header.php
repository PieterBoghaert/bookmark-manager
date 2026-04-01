<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Header extends Component
{
    public $search;

    public function mount($search)
    {
        $this->search = $search;
    }

    public function render()
    {
        return view('livewire.components.header');
    }
}
