<?php

namespace App\Http\Livewire\Plugins;

trait WithLiveValidation
{
    public function updatedWithLiveValidation($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
