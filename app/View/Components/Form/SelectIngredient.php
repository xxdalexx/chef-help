<?php

namespace App\View\Components\Form;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class SelectIngredient extends Component
{
    public string $columnClass;

    public function __construct(?int $cols = null)
    {
        $this->columnClass = $cols
            ? "col-md-$cols"
            : "col";
    }

    public function ingredients(): Collection
    {
        return Ingredient::orderBy('name')->get();
    }

    public function livewireAttributes(): ComponentAttributeBag
    {
        return $this->attributes->thatStartWith('wire');
    }

    public function render()
    {
        return view('components.form.select-ingredient');
    }
}
