<x-card title="Add Ingredient">

    <div class="row">
        <x-form.select-ingredient wire:model="ingredientInput" cols="6" />
        <x-form.text-input name="unitQuantityInput" label-name="Quantity" />
        <x-form.select-units wire:model="unitInput" />
        <div class="col">
            <div class="form-check form-switch">
                <input wire:model="cleanedInput" class="form-check-input" type="checkbox" role="switch" id="cleaned">
                <label class="form-check-label" for="cleaned">Cleaned</label>
            </div>
            <div class="form-check form-switch">
                <input wire:model="cookedInput" class="form-check-input" type="checkbox" role="switch" id="cooked">
                <label class="form-check-label" for="cooked">Cooked</label>
            </div>
        </div>
    </div>

    <x-button.block wire:click="addIngredient" style-type="success" text="Add" class="mt-2"/>

</x-card>
