<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithSearch;
use App\Models\Recipe;
use Livewire\WithPagination;

class RecipeIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch;

    public bool $showCreateForm = false;

    public string $menuCategoryFilter = '';

    protected $queryString = [
        'menuCategoryFilter' => ['except' => '', 'as' => 'menuCategory']
    ];

    public function render()
    {
        $recipeQuery = Recipe::search($this->searchString)->with(['items.ingredient.asPurchased', 'menuCategory']);

        if (! empty($this->menuCategoryFilter)) {
            $recipeQuery->whereHas('menuCategory', function ($query) {
                return $query->where('name', $this->menuCategoryFilter);
            });
        }

        return view('livewire.recipe-index')
            ->withRecipes($recipeQuery->paginate(10));
    }
}
