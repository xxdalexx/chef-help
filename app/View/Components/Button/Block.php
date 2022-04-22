<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Block extends Component
{
    public function __construct(
        public string $text,
        public string $styleType = 'primary',
        public bool $showSpinner = true
    )
    {
    }

    public function divAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->whereDoesntStartWith('wire:click')
            ->merge(['class' => 'd-grid gap-2']);
    }

    public function buttonAttributes(): ComponentAttributeBag
    {
        return $this->attributes->thatStartWith('wire:click');
    }

    public function livewireMethod(): string
    {
        //will give the method name that was passed into wire:click
        return $this->buttonAttributes()->first();
    }

    public function render()
    {
        return view('components.button.block');
    }
}
