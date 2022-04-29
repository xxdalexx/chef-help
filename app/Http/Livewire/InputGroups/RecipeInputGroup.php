<?php

namespace App\Http\Livewire\InputGroups;

use App\Models\MenuCategory;

trait RecipeInputGroup
{
    public string $recipeNameInput = '';

    public string $menuPriceInput  = '';

    public string $portionsInput   = '';

    public string $menuCategoryInput = '';

    public string $costingGoalInput = '';

    public function rules(): array
    {
        return [
            'recipeNameInput'   => 'required',
            'menuPriceInput'    => 'required|numeric',
            'portionsInput'     => 'required|numeric',
            'menuCategoryInput' => 'exists:menu_categories,id',
            'costingGoalInput'  => 'nullable|numeric'
        ];
    }

    public function resetInputs(): void
    {
        $this->recipeNameInput = '';
        $this->menuPriceInput = '';
        $this->portionsInput = '';
        $this->menuCategoryInput = MenuCategory::first()->id ?? '0';
        $this->costingGoalInput = '';
    }
}
