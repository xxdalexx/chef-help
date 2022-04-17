<?php

namespace App\Http\Livewire\Plugins;

trait WithSearch
{
    public string $searchString = '';

    public function updatingSearchString()
    {
        $this->resetPage();
    }
}
