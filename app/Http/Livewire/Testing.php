<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Testing extends LivewireBaseComponent
{
    public function test()
    {
        sleep(2);
        $this->alertWithToast('Done Sleeping.');
    }

    public function render()
    {
        return view('livewire.testing');
    }
}
