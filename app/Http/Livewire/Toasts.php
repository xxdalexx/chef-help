<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Toasts extends Component
{
    public array $messages = [];

    public int $next = 0;

    protected $listeners = ['alertWithToast' => 'addMessage'];

    public function addMessage($messageText)
    {
        $this->messages['toast-' . $this->next] = $messageText;
        $this->emit('showToast', 'toast-' . $this->next);
        $this->next += 1;
    }

    public function render()
    {
        return view('livewire.toasts');
    }
}
