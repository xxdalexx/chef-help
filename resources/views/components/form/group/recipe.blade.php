<div class="row">
    <x-form.text-input name="recipeNameInput" label-name="Recipe Name" cols="4" />
    <x-form.price-input name="menuPriceInput" label-name="Menu Price" />
    <x-form.text-input name="portionsInput" label-name="Portions" />
    <x-form.select-menu-category wire:model="menuCategoryInput" cols="3"/>
</div>
