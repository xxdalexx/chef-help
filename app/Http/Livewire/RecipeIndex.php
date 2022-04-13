<?php

namespace App\Http\Livewire;

use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;

class RecipeIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createRecipe()
    {
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
            ->withRecipes(Recipe::search($this->search)->paginate(10));
    }
}
