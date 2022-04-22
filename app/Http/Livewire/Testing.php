<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Testing extends LivewireBaseComponent
{
    public function test()
    {
        $this->alertWithToast('Message Text');
    }

    public function render()
    {
        return view('livewire.testing');
    }
}
