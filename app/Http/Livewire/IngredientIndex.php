<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithLiveValidation;
use App\Http\Livewire\Plugins\WithSearch;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use Livewire\WithPagination;

class IngredientIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch, WithLiveValidation;

    public bool $showCreateForm = false;

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)
            ->orderBy('name')
            ->withCount(['recipeItems', 'locations'])
            ->paginate(10);

        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
