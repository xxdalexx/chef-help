<?php

namespace App\Http\Livewire;

use App\Models\Recipe;
use Livewire\Component;
use Livewire\WithPagination;

class RecipeIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showCreateForm = true;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.recipe-index')
            ->withRecipes(Recipe::search($this->search)->paginate(10));
    }
}
