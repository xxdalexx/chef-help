<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class TextInput extends Component
{
    public string $columnClass;

    public function __construct(
        public string $name,
        public string $labelName,
        ?int $cols = null
    )
    {
        $this->columnClass = $cols
            ? "col-md-$cols"
            : "col";
    }

    public function render()
    {
        return view('components.form.text-input');
    }
}
