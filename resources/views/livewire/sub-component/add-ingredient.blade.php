<div>
    <x-card title="Add Ingredient" close-click="$emit('hideEditArea')">

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

                <div class="form-floating col-md-6" wire:key="select-ingredient">
                    <select wire:click="$emit('showModal', 'addIngredientModal')" wire:model="ingredientInput" class="form-select" id="ingredient">
                        <option value="{{ $ingredientInput }}">{{ $this->getSelectedIngredientName() }}</option>
                    </select>
                    <label for="ingredient" class="ms-2">Ingredient</label>
                </div>

                <x-form.text-input name="unitQuantityInput" label-name="Quantity" />
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
                    <x-form.price-input name="apPriceInput" label-name="Price" />
                    <x-form.text-input name="apQuantityInput" label-name="Quantity" />
                    <x-form.select-units wire:model="apUnitInput" />
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

    <x-modal wire:click="test" title="Ingredient Search" id="addIngredientModal" close-button-text="Cancel" noActionButton>
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3" wire:key="">
                    <input wire:model="ingredientSearch"
                           type="text"
                           class="form-control"
                           placeholder="Search">

                    <label>
                        Search
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="list-group">

                @foreach($this->getIngredientSearchList() as $ingredient)
                    <a wire:click.prevent="$set('ingredientInput', {{ $ingredient->id }})"
                       href=""
                       class="list-group-item list-group-item-action"
                       data-bs-dismiss="modal"
                    >
                        {{ $ingredient->name }}
                    </a>
                @endforeach

                </div>
            </div>
        </div>
    </x-modal>

</div>
