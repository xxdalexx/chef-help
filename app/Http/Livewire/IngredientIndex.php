<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use Livewire\Component;
use Livewire\WithPagination;

class IngredientIndex extends Component
{
    use WithPagination;

    public string $searchString = '';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $ingredients = Ingredient::search($this->searchString)->paginate(5);
        return view('livewire.ingredient-index')
            ->withIngredients($ingredients);
    }
}
