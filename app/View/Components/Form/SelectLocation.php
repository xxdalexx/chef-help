<?php

namespace App\View\Components\Form;

use App\Models\Ingredient;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\Component;

class SelectLocation extends Component
{
    public string $columnClass;

    public function __construct(public Ingredient $ingredient, ?int $cols = null)
    {
        $this->columnClass = $cols
            ? "col-md-$cols"
            : "col";
    }

    public function locations(): EloquentCollection
    {
        return Location::all()->except($this->ingredient->locations->pluck('id')->toArray());
    }

    public function render()
    {
        return view('components.form.select-location');
    }
}
