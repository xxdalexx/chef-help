<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Str;
use Livewire\Component;

class Testing extends LivewireBaseComponent
{
    public function test()
    {
        sleep(2);
        $this->alertWithToast('Done Sleeping.');
    }

    public function render()
    {
        Recipe::preventLazyLoading(false);
        $recipe = Recipe::with(['items.ingredient.asPurchased', 'menuCategory'])->find(3);

        foreach ($recipe->ingredients as $ingredient) {
            $ingredient->varName = '$' . Str::of($ingredient->name)->camel();
        }

        return view('livewire.testing')->withRecipe($recipe);
    }
}
