<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use Livewire\WithPagination;

class IngredientIndex extends LivewireBaseComponent
{
    use WithPagination;

    public string $searchString = '';

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)->paginate(5);
        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
