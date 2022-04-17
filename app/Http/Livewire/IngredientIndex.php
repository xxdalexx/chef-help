<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithSearch;
use App\Models\Ingredient;
use Livewire\WithPagination;

class IngredientIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch;

    public bool $showCreateForm = false;

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)->paginate(5);
        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
