<?php

namespace App\View\Components\Ls;

use Illuminate\View\Component;

class SubmitButton extends Component
{
    public function __construct(
        public string $targets,
        public string $text = 'Submit'
    )
    {
    }

    public function render()
    {
        return view('components.ls.submit-button');
    }
}
