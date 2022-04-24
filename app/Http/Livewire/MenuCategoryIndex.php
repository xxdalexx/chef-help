<?php

namespace App\Http\Livewire;

use App\Models\MenuCategory;

class MenuCategoryIndex extends LivewireBaseComponent
{
    public string $nameInput = '';

    public string $costingGoalInput = '';

    public string $menuCategoryEditing = '';

    public string $wantingToDelete = '';

    public string $categoryIdToMoveTo = '';

    protected array $rules = [
        'nameInput' => 'required',
        'costingGoalInput' => 'required|numeric',
    ];

    public function getCardTitleProperty(): string
    {
        if (empty($this->menuCategoryEditing)) {
            return 'Create';
        }
        return 'Edit';
    }

    public function getCategoriesForMoveProperty(): \Illuminate\Support\Collection
    {
        $categories = MenuCategory::select(['id', 'name'])->whereNotIn('id', [$this->wantingToDelete])->get();
        $this->categoryIdToMoveTo = $categories->first()->id ?? '';
        return $categories;
    }

    public function loadMenuCategoryToEdit(MenuCategory $menuCategory): void
    {
        $this->nameInput = $menuCategory->name;
        $this->costingGoalInput = $menuCategory->costing_goal;
        $this->menuCategoryEditing = $menuCategory->id;
    }

    public function resetInputs(): void
    {
        $this->nameInput = '';
        $this->costingGoalInput = '';
        $this->menuCategoryEditing = '';
    }

    public function process(): void
    {
        $this->validate();

        if (empty($this->menuCategoryEditing)) {
            $this->createMenuCategory();
        } else {
            $this->editMenuCategory();
        }

        $this->resetInputs();
    }

    public function createMenuCategory(): void
    {
        MenuCategory::create([
            'name' => $this->nameInput,
            'costing_goal' => $this->costingGoalInput
        ]);

        $this->alertWithToast($this->nameInput . ' created.');
    }

    public function editMenuCategory(): void
    {
        $menuCategory = MenuCategory::findOrFail($this->menuCategoryEditing);

        $menuCategory->update([
            'name' => $this->nameInput,
            'costing_goal' => $this->costingGoalInput
        ]);

        $this->alertWithToast('Updated.');
    }

    public function moveAllToCategory(): void
    {
        $menuCategoryToMoveFrom = MenuCategory::findOrFail($this->wantingToDelete);

        foreach ($menuCategoryToMoveFrom->recipes as $recipe) {
            $recipe->update([
                'menu_category_id' => $this->categoryIdToMoveTo
            ]);
        }

        $this->alertWithToast('Recipes moved.');
        $this->deleteMenuCategory($menuCategoryToMoveFrom);
    }

    public function deleteMenuCategory(MenuCategory $menuCategory): void
    {
        $menuCategory->loadCount('recipes');

        if ($menuCategory->recipes_count != 0) {
            $this->wantingToDelete = $menuCategory->id;
            $this->emit('showModal', 'moveModal');
            return;
        }

        $menuCategory->delete();
        $this->alertWithToast('Deleted.');
    }

    public function render()
    {
        return view('livewire.menu-category-index')
            ->withMenuCategories(
                MenuCategory::withCount('recipes')->orderBy('name')->paginate()
            );
    }
}
