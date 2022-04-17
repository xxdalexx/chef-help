<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithSearch;
use App\Models\Recipe;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class RecipeIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch;

    public bool $showCreateForm = false;

    public string $recipeNameInput = '';
    public string $menuPriceInput  = '';
    public string $portionsInput   = '';

    protected array $rules = [
        'recipeNameInput' => 'required',
        'menuPriceInput'  => 'required|numeric',
        'portionsInput'   => 'required|numeric',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createRecipe()
    {
        //Todo: This will need to be refactored when more currency symbols are supported.
        $strOfPrice = Str::of($this->menuPriceInput);
        if ($strOfPrice->startsWith('$')) {
            $this->menuPriceInput = $strOfPrice->after('$');
        }

        $this->validate();

        Recipe::create([
            'name'     => $this->recipeNameInput,
            'price'    => money($this->menuPriceInput),
            'portions' => $this->portionsInput,
        ]);

        $this->recipeNameInput = '';
        $this->menuPriceInput = '';
        $this->portionsInput = '';
    }

    public function render()
    {
        return view('livewire.recipe-index')
            ->withRecipes(Recipe::search($this->searchString)->paginate(10));
    }
}
