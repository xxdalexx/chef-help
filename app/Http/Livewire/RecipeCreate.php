<?php

namespace App\Http\Livewire;

use App\Http\Livewire\InputGroups\RecipeInputGroup;
use App\Http\Livewire\Plugins\WithLiveValidation;
use App\Models\MenuCategory;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class RecipeCreate extends LivewireBaseComponent
{
    use WithLiveValidation;

    use RecipeInputGroup;

    public function mount(): void
    {
        $this->resetInputs();
    }

    public function resetInputs(): void
    {
        $this->recipeNameInput = '';
        $this->menuPriceInput = '';
        $this->portionsInput = '';
        $this->menuCategoryInput = MenuCategory::first()->id ?? '0';
    }

    public function create()//: Livewire\Redirector
    {
        //This will need to be refactored when more currency symbols are supported.
        $strOfPrice = Str::of($this->menuPriceInput);
        if ($strOfPrice->startsWith('$')) {
            $this->menuPriceInput = $strOfPrice->after('$');
        }

        $this->validate();

        $recipe = Recipe::create([
            'name'     => $this->recipeNameInput,
            'price'    => money($this->menuPriceInput),
            'portions' => $this->portionsInput,
            'menu_category_id' => $this->menuCategoryInput,
        ]);

        return redirect()->route('recipe.show', $recipe);
    }

    public function render(): View
    {
        return view('livewire.recipe-create');
    }
}
