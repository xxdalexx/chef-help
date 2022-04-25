<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\Refreshable;
use App\Models\AsPurchased;
use App\Models\Ingredient;

class IngredientShow extends LivewireBaseComponent
{
    use Refreshable;

    public ?Ingredient $ingredient;

    public bool $hasAsPurchased = false;

    /*
    |--------------------------------------------------------------------------
    | Edit Parameters
    |--------------------------------------------------------------------------
    */

    public string $nameInput         = '';
    public string $cleanedYieldInput = '';
    public string $cookedYieldInput  = '';

    protected array $rulesForEdit = [
        'nameInput'         => 'required',
        'cleanedYieldInput' => 'required|numeric|between:1,100',
        'cookedYieldInput'  => 'required|numeric|between:1,100',
    ];

    /*
    |--------------------------------------------------------------------------
    | As Purchased Parameters
    |--------------------------------------------------------------------------
    */

    public string $apQuantityInput = '';
    public string $apUnitInput     = '';
    public string $apPriceInput    = '';

    protected array $rulesForAp = [
        'apQuantityInput' => 'required|numeric',
        'apUnitInput'     => 'required',
        'apPriceInput'    => 'required|numeric',
    ];

    /*
    |--------------------------------------------------------------------------
    | Location Parameters
    |--------------------------------------------------------------------------
    */

    public string $locationInput = '';

    public function mount(): void
    {
        $this->ingredient->load(['asPurchased', 'locations']);

        if (! empty($this->ingredient->asPurchased)) {
            $this->hasAsPurchased = true;
            $this->apQuantityInput = (string) $this->ingredient->asPurchased->quantity;
            $this->apUnitInput = $this->ingredient->asPurchased->unit->value;
        }

        $this->locationInput = $this->ingredient->inverseLocationIds()[0] ?? '';

        $this->setEditProperties();
    }

    protected function setEditProperties(): void
    {
        $this->nameInput = $this->ingredient->name;
        $this->cleanedYieldInput = $this->ingredient->cleaned_yield;
        $this->cookedYieldInput = $this->ingredient->cooked_yield;
    }

    public function editIngredient(): void
    {
        $this->validate($this->rulesForEdit);

        $this->ingredient->update([
            'name' => $this->nameInput,
            'cooked_yield' => $this->cookedYieldInput,
            'cleaned_yield' => $this->cleanedYieldInput
        ]);

        $this->ingredient->refresh();
        $this->alertWithToast($this->ingredient->name . ' updated.');
        $this->setEditProperties();
    }

    public function addAsPurchased(): void
    {
        $this->validate($this->rulesForAp);

        AsPurchased::create([
            'price' => money($this->apPriceInput),
            'unit' => findMeasurementUnitEnum($this->apUnitInput),
            'quantity' => $this->apQuantityInput,
            'ingredient_id' => $this->ingredient->id
        ]);

        $this->hasAsPurchased = true;
        $this->apPriceInput = '';
        $this->ingredient->refresh();
        $this->alertWithToast('Purchase pricing updated.');
    }

    public function addLocation(): void
    {
        $this->ingredient->locations()->attach($this->locationInput);
        $this->alertWithToast('Location Added.');
        $this->ingredient->refresh();
    }

    public function removeLocation(int $id): void
    {
        $this->ingredient->locations()->detach($id);
        $this->alertWithToast('Location Removed.');
        $this->ingredient->refresh();
    }

    public function render()
    {
        $this->ingredient->loadCount('recipeItems');
        $this->ingredient->asPurchasedAll->loadVariances();
        return view('livewire.ingredient-show');
    }
}
