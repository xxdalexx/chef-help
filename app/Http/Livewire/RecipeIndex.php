<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Plugins\WithLiveValidation;
use App\Http\Livewire\Plugins\WithSearch;
use App\Models\MenuCategory;
use App\Models\Recipe;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class RecipeIndex extends LivewireBaseComponent
{
    use WithPagination, WithSearch, WithLiveValidation;

    public bool $showCreateForm = false;

    public string $menuCategoryFilter = '';

    protected $queryString = [
        'menuCategoryFilter' => ['except' => '', 'as' => 'menuCategory']
    ];

    public string $recipeNameInput = '';
    public string $menuPriceInput  = '';
    public string $portionsInput   = '';
    public string $menuCategoryInput = '';

    protected array $rules = [
        'recipeNameInput' => 'required',
        'menuPriceInput'  => 'required|numeric',
        'portionsInput'   => 'required|numeric',
        'menuCategoryInput' => 'exists:menu_categories,id'
    ];

    public function mount(): void
    {
        $this->resetInputs();
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
            'menu_category_id' => $this->menuCategoryInput,
        ]);

        $this->setSearch($this->recipeNameInput);
        $this->alertCreated();
        $this->resetInputs();
    }

    public function alertCreated(): void
    {
        $message = 'Recipe: ' . $this->recipeNameInput . ' successfully created';
        $this->alertWithToast($message);
    }

    public function resetInputs(): void
    {
        $this->recipeNameInput = '';
        $this->menuPriceInput = '';
        $this->portionsInput = '';
        $this->menuCategoryInput = MenuCategory::first()->id ?? '0';
    }

    public function render()
    {
        $recipeQuery = Recipe::search($this->searchString)->with(['items.ingredient.asPurchased', 'menuCategory']);

        if (! empty($this->menuCategoryFilter)) {
            $recipeQuery->whereHas('menuCategory', function ($query) {
                return $query->where('name', $this->menuCategoryFilter);
            });
        }

        return view('livewire.recipe-index')
            ->withRecipes($recipeQuery->paginate(10));
    }
}
