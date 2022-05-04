<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\Refreshable;
use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Support\Str;

class RecipeShow extends LivewireBaseComponent
{
    use Refreshable;

    public ?Recipe $recipe;

    public ?RecipeItem $editingRecipeItem;

    public string $editArea = '';

    /*
    |--------------------------------------------------------------------------
    | Edit Item Properties
    |--------------------------------------------------------------------------
    */

    public string $editUnitInput     = '';
    public string $editQuantityInput = '';
    public string $editCleanedInput  = '';
    public string $editCookedInput   = '';

    protected array $rules = [
        'editUnitInput'     => 'required',
        'editQuantityInput' => 'required|numeric',
        'editCleanedInput'  => 'boolean',
        'editCookedInput'   => 'boolean'
    ];

    protected $listeners = ['hideEditArea'];

    public function updated($property): void
    {
        $this->validateOnly($property, $this->rules);
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function updateItem(): void
    {
        $this->validate();

        $this->editingRecipeItem->update([
            'quantity' => $this->editQuantityInput,
            'unit'     => findMeasurementUnitEnum($this->editUnitInput),
            'cooked'   => (bool) $this->editCookedInput,
            'cleaned'  => (bool) $this->editCleanedInput
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
        $this->editUnitInput     = $item->unit->value;
        $this->editQuantityInput = (string)$item->quantity;
        $this->editCleanedInput  = $item->cleaned ? '1' : '';
        $this->editCookedInput   = $item->cooked ? '1' : '';

        $this->editArea = 'editItem';
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
        $this->recipe->load(['items.ingredient.asPurchased', 'items.ingredient.crossConversions']);
        return view('livewire.recipe-show');
    }
}
