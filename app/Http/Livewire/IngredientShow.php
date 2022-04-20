<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use Livewire\Component;

class IngredientShow extends Component
{
    public ?Ingredient $ingredient;

    public function mount()
    {
        $this->ingredient->load('asPurchased');
    }

    public function render()
    {
        return view('livewire.ingredient-show');
    }
}
