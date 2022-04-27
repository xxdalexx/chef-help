<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public string $sizeClass = '';

    public function __construct(
        public string $title,
        public ?string $size = null,
        public string $id = 'modal',
        public string $actionButtonText = 'Submit',
        public string $closeButtonText = 'Close',
    )
    {
        $this->sizeClass = match($size) {
            'sm', 'small' => 'modal-sm',
            'lg', 'large' => 'modal-lg',
            'xl' => 'modal-xl',
            default => ''
        };
    }

    public function render()
    {
        return view('components.modal');
    }
}
