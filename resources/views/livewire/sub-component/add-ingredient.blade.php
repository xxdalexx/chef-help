<x-card title="Add Ingredient">

        <div class="row">
            <div class="d-grid col-6">
                <input wire:model="showingExistingIngredient" type="radio" class="btn-check" name="btnradio" id="btnradio1" value="1">
                <label class="btn btn-outline-primary" for="btnradio1">Existing Ingredient</label>
            </div>
            <div class="d-grid col-6">
                <input wire:model="showingExistingIngredient" type="radio" class="btn-check" name="btnradio" id="btnradio2" value="0">
                <label class="btn btn-outline-primary" for="btnradio2">Create New Ingredient</label>
            </div>
        </div>
    <hr>

    @if($showingExistingIngredient)
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

    @else
    New
    @endif

</x-card>
