<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class ToggleSwitch extends Component
{
    public function __construct(public string $labelName)
    {
    }

    public function livewireAttributes(): ComponentAttributeBag
    {
        return $this->attributes->thatStartWith('wire');
    }

    public function render()
    {
        return view('components.form.toggle-switch');
    }
}
