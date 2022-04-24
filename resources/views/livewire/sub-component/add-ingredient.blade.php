<div>
<x-card title="Add Ingredient">

    <div class="row">
        <div class="d-grid col-6">
            <input wire:model="showingExistingIngredient" type="radio" class="btn-check" id="existingIngredientRadio" value="1">
            <label class="btn btn-outline-primary" for="existingIngredientRadio">Existing Ingredient</label>
        </div>
        <div class="d-grid col-6">
            <input wire:model="showingExistingIngredient" type="radio" class="btn-check" id="newIngredientRadio" value="0">
            <label class="btn btn-outline-primary" for="newIngredientRadio">Create New Ingredient</label>
        </div>
    </div>
    <hr>

    <form wire:submit.prevent="addIngredient">
        @if($showingExistingIngredient)
        <div class="row" wire:key="existing">
            <x-form.select-ingredient wire:model="ingredientInput" cols="6" />
            <div class="col">
                <x-form.text-input name="unitQuantityInput" label-name="Quantity" />
            </div>
            <x-form.select-units wire:model="unitInput" />
            <div class="col">
                <x-form.toggle-switch wire:model="cleanedInput" label-name="Cleaned" />
                <x-form.toggle-switch wire:model="cookedInput" label-name="Cooked" />
            </div>
        </div>

        @else

        <div class="div" wire:key="new">
            <p class="ms-2">
                Ingredient Information
            </p>
            <div class="row">
                <x-form.text-input name="nameInput" label-name="Name" cols="8" />
                <x-form.text-input name="cleanedYieldInput" label-name="Cleaned Yield %" />
                <x-form.text-input name="cookedYieldInput" label-name="Cooked Yield %" />
            </div>

            <hr>
            <p class="ms-2">
                As Purchased
            </p>
            <div class="row">
                <x-form.text-input name="apQuantityInput" label-name="Quantity" />
                <x-form.select-units wire:model="apUnitInput" />
                <x-form.price-input name="apPriceInput" label-name="Price" />
            </div>

            <hr>
            <p class="ms-2">
                Used In Recipe
            </p>
            <div class="row">
                <x-form.text-input name="unitQuantityInput" label-name="Quantity" />
                <x-form.select-units wire:model="unitInput" />
                <div class="col">
                    <x-form.toggle-switch wire:model="cleanedInput" label-name="Cleaned" />
                    <x-form.toggle-switch wire:model="cookedInput" label-name="Cooked" />
                </div>
            </div>
        </div>
        @endif

        <x-ls.submit-button targets="addIngredient" />
    </form>
</x-card>
</div>
