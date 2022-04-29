<div>
    <div class="row">
        <x-form.text-input name="recipeNameInput" label-name="Recipe Name"/>
    </div>

    <div class="row">
        <x-form.price-input name="menuPriceInput" label-name="Menu Price"/>
    </div>

    <div class="row">
        <x-form.text-input name="portionsInput" label-name="Portions"/>
    </div>

    <div class="row">
        <div class="col">
            <x-form.select-menu-category wire:model="menuCategoryInput" num="2"/>
        </div>
    </div>

    <div class="row">
        <x-form.text-input name="costingGoalInput" label-name="Costing Goal Override (Optional)"/>
    </div>

    <button type="submit" class="visually-hidden"></button>
</div>
