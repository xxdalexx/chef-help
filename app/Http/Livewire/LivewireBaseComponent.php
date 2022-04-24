<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LivewireBaseComponent extends Component
{
    protected string $paginationTheme = 'bootstrap';

    public function alertWithToast(string $message): void
    {
        $this->emit('alertWithToast', $message);
    }

    public function showModal($modalId = 'modal'): void
    {
        $this->emit('showModal', $modalId);
    }
}
