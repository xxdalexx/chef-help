<?php

namespace App\Http\Livewire;

use App\Models\AsPurchased;
use App\Models\Ingredient;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class IngredientCreate extends Component
{
    public bool $createAsPurchased = true;

    public string $nameInput = '';

    public string $cleanedInput = '100';

    public string $cookedInput = '100';

    public string $apQuantityInput = '1';

    public string $apUnitInput = 'oz';

    public string $apPriceInput = '';

    protected array $rules = [];

    public function rules(): array
    {
        return [
            'nameInput'       => 'required',
            'cleanedInput'    => 'required|numeric|between:1,100',
            'cookedInput'     => 'required|numeric|between:1,100',

            'apQuantityInput' => ['numeric', Rule::requiredIf($this->createAsPurchased)],
            'apUnitInput'     => [Rule::requiredIf($this->createAsPurchased)],
            'apPriceInput'    => ['numeric', Rule::requiredIf($this->createAsPurchased)],
        ];
    }

    public function resetInputs(): void
    {
        $this->nameInput    = '';
        $this->cleanedInput = '100';
        $this->cookedInput  = '100';
        $this->apQuantityInput = '1';
        $this->apUnitInput = 'oz';
        $this->apPriceInput = '';
    }

    public function create()//: Livewire\Redirector
    {
        $this->validate();

        $ingredient = Ingredient::create([
            'name'          => $this->nameInput,
            'cooked_yield'  => $this->cookedInput,
            'cleaned_yield' => $this->cleanedInput
        ]);

        if ($this->createAsPurchased) {
            $this->createAsPurchasedForIngredient($ingredient);
        }

        return redirect()->route('ingredient.show', $ingredient);
    }

    protected function createAsPurchasedForIngredient(Ingredient $ingredient)
    {
        $asPurchased = AsPurchased::create([
            'quantity' => $this->apQuantityInput,
            'unit' => findMeasurementUnitEnum($this->apUnitInput),
            'price' => money($this->apPriceInput),
            'ingredient_id' => $ingredient->id
        ]);
    }

    public function render(): View
    {
        return view('livewire.ingredient-create');
    }
}
