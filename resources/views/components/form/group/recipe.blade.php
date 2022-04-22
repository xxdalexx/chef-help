<div class="row">
    <div class="col-4">
        <x-form.text-input name="recipeNameInput" label-name="Recipe Name" />
    </div>

    <div class="col">
        <x-form.price-input name="menuPriceInput" label-name="Menu Price" />
    </div>

    <div class="col">
        <x-form.text-input name="portionsInput" label-name="Portions" />
    </div>

    <div class="col-3">
        <x-form.select-menu-category wire:model="menuCategoryInput" />
    </div>
</div>
