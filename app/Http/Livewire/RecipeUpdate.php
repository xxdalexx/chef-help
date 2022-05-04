<?php

namespace App\Http\Livewire;

use App\Http\Livewire\InputGroups\RecipeInputGroup;
use App\Http\Livewire\Plugins\WithLiveValidation;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class RecipeUpdate extends LivewireBaseComponent
{
    use WithLiveValidation;

    use RecipeInputGroup;

    public Recipe $recipe;

    public function mount(): void
    {
        $this->recipeNameInput = $this->recipe->name;
        $this->menuPriceInput = $this->recipe->getPriceAsString(false);
        $this->portionsInput = $this->recipe->portions;
        $this->menuCategoryInput = $this->recipe->menu_category_id;
        $this->costingGoalInput = $this->recipe->costing_goal->isGreaterThan(0)
            ? $this->recipe->costing_goal
            : '';
    }

    public function update(): void
    {
        //This will need to be refactored when more currency symbols are supported.
        $strOfPrice = Str::of($this->menuPriceInput);
        if ($strOfPrice->startsWith('$')) {
            $this->menuPriceInput = $strOfPrice->after('$');
        }

        $this->validate();

        if (empty($this->costingGoalInput)) {
            $this->costingGoalInput = $this->recipe->costing_goal;
        }

        $this->recipe->update([
            'name'     => $this->recipeNameInput,
            'price'    => money($this->menuPriceInput),
            'portions' => $this->portionsInput,
            'menu_category_id' => $this->menuCategoryInput,
            'costing_goal' => $this->costingGoalInput
        ]);

        $this->alertWithToast($this->recipe->name . ' updated.');
        $this->recipe->refresh();
        $this->emitUp('refresh');
    }

    public function render(): View
    {
        return view('livewire.recipe-update');
    }
}
