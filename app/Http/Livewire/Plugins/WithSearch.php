<?php

namespace App\Http\Livewire\Plugins;

trait WithSearch
{
    public string $searchString = '';

    public function setSearch($string)
    {
        $this->searchString = $string;
    }

    public function updatingSearchString()
    {
        $this->resetPage();
    }
}
