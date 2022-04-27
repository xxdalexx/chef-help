<?php

namespace App\Http\Livewire\InputGroups;

trait RecipeInputGroup
{
    public string $recipeNameInput = '';

    public string $menuPriceInput  = '';

    public string $portionsInput   = '';

    public string $menuCategoryInput = '';

    public function rules(): array
    {
        return [
            'recipeNameInput'   => 'required',
            'menuPriceInput'    => 'required|numeric',
            'portionsInput'     => 'required|numeric',
            'menuCategoryInput' => 'exists:menu_categories,id'
        ];
    }
}
