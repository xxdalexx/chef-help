<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\Refreshable;
use App\Models\Recipe;
use Illuminate\Support\Str;

class RecipeShow extends LivewireBaseComponent
{
    use Refreshable;

    public ?Recipe $recipe;

    public ?string $editArea = '';

    public string $recipeNameInput = '';

    public string $menuPriceInput = '';

    public string $portionsInput = '';

    protected array $rules = [
        'recipeNameInput' => 'required',
        'menuPriceInput'  => 'required|numeric',
        'portionsInput'   => 'required|numeric',
    ];

    public function mount(Recipe $recipe): void
    {
        $this->recipe          = $recipe->load('items.ingredient.asPurchased');
        $this->recipeNameInput = $recipe->name;
        $this->menuPriceInput  = $recipe->getPriceAsString();
        $this->portionsInput   = $recipe->portions;
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function updateRecipe(): void
    {
        //Todo: This will need to be refactored when more currency symbols are supported.
        $strOfPrice = Str::of($this->menuPriceInput);
        if ($strOfPrice->startsWith('$')) {
            $this->menuPriceInput = $strOfPrice->after('$');
        }

        $this->validate();

        $this->recipe->update([
            'name'     => $this->recipeNameInput,
            'price'    => money($this->menuPriceInput),
            'portions' => $this->portionsInput
        ]);

        $this->editArea = '';
    }

    /*
    |--------------------------------------------------------------------------
    | Page Toggles
    |--------------------------------------------------------------------------
    */

    public function showAddIngredient(): void
    {
        $this->editArea = 'ingredient';
    }

    public function showEditRecipe(): void
    {
        $this->editArea = 'recipe';
    }

    public function hideEditArea(): void
    {
        $this->editArea = '';
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.recipe-show');
    }
}
