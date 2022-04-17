<x-card title="Add Ingredient">

    <div class="row">
        <x-form.select-ingredient wire:model="ingredientInput" cols="6" />
        <x-form.text-input name="unitQuantityInput" label-name="Quantity" />
        <x-form.select-units wire:model="unitInput" />
        <div class="col">
            <x-form.toggle-switch wire:model="cleanedInput" label-name="Cleaned" />
            <x-form.toggle-switch wire:model="cookedInput" label-name="Cooked" />
        </div>
    </div>

    <x-button.block wire:click="addIngredient" style-type="success" text="Add" class="mt-2"/>

</x-card>
