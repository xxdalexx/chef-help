<?php

namespace App\Http\Livewire;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Support\Str;
use Livewire\Component;

class RecipeShow extends Component
{
    public ?Recipe $recipe;

    public ?string $editArea = '';

    /*
    |--------------------------------------------------------------------------
    | Edit Recipe Inputs
    |--------------------------------------------------------------------------
    */

    public string $recipeNameInput = '';
    public string $menuPriceInput  = '';
    public string $portionsInput   = '';

    /*
    |--------------------------------------------------------------------------
    | Add Ingredient Inputs
    |--------------------------------------------------------------------------
    */

    public string $ingredientInput = '';
    public string $unitInput         = 'oz';
    public string $unitQuantityInput = '';
    public string $cleanedInput = '';
    public string $cookedInput = '';

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

        $this->ingredientInput = Ingredient::first()->id;
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

    public function addIngredient()
    {
        $recipeItem = RecipeItem::make([
            'ingredient_id' => $this->ingredientInput,
            'quantity' => $this->unitQuantityInput,
            'unit' => findMeasurementUnitEnum($this->unitInput),
            'cooked' => (bool) $this->cookedInput,
            'cleaned' => (bool) $this->cleanedInput
        ]);

        $this->recipe->items()->save($recipeItem);
        $this->recipe->refresh();
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
