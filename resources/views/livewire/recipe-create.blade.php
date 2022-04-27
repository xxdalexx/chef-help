<x-modal title="Create New Recipe"
    action-button-text="Create"
    id="createRecipeModal"
    size="medium"
    wire:click="create"
>
    <form wire:submit.prevent="create">
        <div class="row">
            <x-form.text-input name="recipeNameInput" label-name="Recipe Name" />
        </div>

        <div class="row">
            <x-form.price-input name="menuPriceInput" label-name="Menu Price" />
        </div>

        <div class="row">
            <x-form.text-input name="portionsInput" label-name="Portions" />
        </div>

        <div class="row">
            <div class="col">
                <x-form.select-menu-category wire:model="menuCategoryInput" num="2" />
            </div>
        </div>
        <button type="submit" class="visually-hidden"></button>
    </form>
</x-modal>
