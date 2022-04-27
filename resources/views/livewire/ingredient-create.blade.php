<x-modal title="Create New Ingredient"
         action-button-text="Create"
         id="createIngredientModal"
         size="medium"
         wire:click="create"
>
    <form wire:submit.prevent="create">
        <div class="row">
            <x-form.text-input name="nameInput" label-name="Name" />
        </div>
        <div class="row">
            <x-form.text-input name="cleanedInput" label-name="Cleaned Yield %" />
        </div>
        <div class="row">
            <x-form.text-input name="cookedInput" label-name="Cooked Yield %" />
        </div>
        <div class="row mb-3">
            <div class="col-8 offset-2">
                <x-form.toggle-switch wire:model="createAsPurchased" label-name="Add As Purchased Pricing" />
            </div>
        </div>

        @if($createAsPurchased)
        <div class="row">
            <x-form.text-input name="apQuantityInput" label-name="Quantity" />
        </div>
        <div class="row">
            <x-form.select-units wire:model="apUnitInput" />
        </div>
        <div class="row">
            <x-form.price-input name="apPriceInput" label-name="Price" />
        </div>
        @endif

        <button type="submit" class="visually-hidden"></button>
    </form>
</x-modal>
