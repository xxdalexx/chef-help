<?php

namespace App\View\Components\Ls;

use App\Models\MenuCategory;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class SelectMenuCategory extends Component
{
    public string $columnClass;

    public function __construct(?int $cols = null)
    {
        $this->columnClass = $cols
            ? "col-md-$cols"
            : "col";
    }

    public function categories(): \Illuminate\Support\Collection
    {
        return MenuCategory::orderBy('name')->get();
    }

    public function livewireAttributes(): ComponentAttributeBag
    {
        return $this->attributes->thatStartWith('wire');
    }

    public function render()
    {
        return view('components.ls.select-menu-category');
    }
}
