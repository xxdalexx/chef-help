<?php

namespace App\Http\Livewire\AbstractionTraits;

use App\Models\Ingredient;

trait AddIngredientSearchable
{
    public string $ingredientSearch = '';

    public function getIngredientSearchList(): \Illuminate\Database\Eloquent\Collection
    {
        return Ingredient::search($this->ingredientSearch)->limit(10)->get();
    }

    public function getSelectedIngredientName(): string
    {
        $ingredient = Ingredient::find($this->ingredientInput);
        if (empty($ingredient)) {
            return 'Select...';
        }
        return $ingredient->name;
    }

    public function test(): void
    {
        $this->showModal();
    }
}
