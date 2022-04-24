<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\Refreshable;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RecipeShow extends LivewireBaseComponent
{
    use Refreshable;

    public ?Recipe $recipe;

    public ?RecipeItem $editingRecipeItem;

    public string $editArea = 'addIngredient';

    /*
    |--------------------------------------------------------------------------
    | Edit Recipe Properties
    |--------------------------------------------------------------------------
    */

    public string $recipeNameInput   = '';
    public string $menuPriceInput    = '';
    public string $portionsInput     = '';
    public string $menuCategoryInput = '';

    protected array $rules = [
        'recipeNameInput' => 'required',
        'menuPriceInput'  => 'required|numeric',
        'portionsInput'   => 'required|numeric',
        'menuCategoryInput' => 'exists:menu_categories,id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Edit Item Properties
    |--------------------------------------------------------------------------
    */

    public string $editUnitInput     = '';
    public string $editQuantityInput = '';
    public string $editCleanedInput  = '';
    public string $editCookedInput   = '';

    protected array $itemRules = [
        'editUnitInput'     => 'required',
        'editQuantityInput' => 'required|numeric',
        'editCleanedInput'  => 'boolean',
        'editCookedInput'   => 'boolean'
    ];

    public function mount(Recipe $recipe): void
    {
        $this->recipe->load('items.ingredient.asPurchased');
        $this->recipeNameInput = $recipe->name;
        $this->menuPriceInput  = $recipe->getPriceAsString(false);
        $this->portionsInput   = $recipe->portions;
        $this->menuCategoryInput = $recipe->menuCategory->id;
    }

    public function updated($property): void
    {
        $rules = collect($this->rules)->merge($this->itemRules)->toArray();
        $this->validateOnly($property, $rules);
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
            'portions' => $this->portionsInput,
            'menu_category_id' => $this->menuCategoryInput,
        ]);

        $this->editArea = '';
        $this->alertWithToast($this->recipe->name . ' updated.');
        $this->recipe->refresh();
    }

    public function updateItem(): void
    {
        $this->validate($this->itemRules);

        $this->editingRecipeItem->update([
            'quantity' => $this->editQuantityInput,
            'unit'     => findMeasurementUnitEnum($this->editUnitInput),
            'cooked'   => (bool)$this->editCookedInput,
            'cleaned'  => (bool)$this->editCleanedInput
        ]);

        $this->editUnitInput     = '';
        $this->editQuantityInput = '';
        $this->editCleanedInput  = '';
        $this->editCookedInput   = '';

        $this->editArea = '';
        $this->alertWithToast('Ingredient Updated.');
        $this->recipe->refresh();
    }

    public function removeRecipeItem(RecipeItem $item): void
    {
        $item->delete();
        $this->alertWithToast('Ingredient Removed.');
        $this->recipe->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Page Toggles
    |--------------------------------------------------------------------------
    */

    public function showAddIngredient(): void
    {
        $this->editArea = 'addIngredient';
    }

    public function showEditItem(RecipeItem $item): void
    {
        $this->editingRecipeItem = $item->load('ingredient');
//        dd($item);
        $this->editUnitInput     = $item->unit->value;
        $this->editQuantityInput = (string)$item->quantity;
        $this->editCleanedInput  = $item->cleaned ? '1' : '';
        $this->editCookedInput   = $item->cooked ? '1' : '';

        $this->editArea = 'editItem';
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
