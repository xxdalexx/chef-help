<?php

namespace App\Http\Livewire;

use App\Models\MenuCategory;

class MenuCategoryIndex extends LivewireBaseComponent
{
    public string $nameInput = '';

    public string $costingGoalInput = '';

    public string $menuCategoryEditing = '';

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

    public function deleteMenuCategory(MenuCategory $menuCategory): void
    {
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
