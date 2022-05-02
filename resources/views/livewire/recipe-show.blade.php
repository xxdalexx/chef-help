<?php
/** @var \App\Models\Recipe $recipe */
?>
<div>
    <div class="display-1 text-center mb-5">{{ $recipe->name }}</div>

    <div class="row">
        <div class="col-md-8">
            <x-card title="Ingredients">
                <x-livewire.recipe-show.ingredient-table :recipe="$recipe" />
            </x-card>
        </div>

        <div class="col-md-4">

            <x-card title="Recipe Stats" header-class="text-end">
                <x-livewire.recipe-show.stats :recipe="$recipe" />
            </x-card>

        </div>
    </div>

    <div class="row mt-4 mb-5">
        <div class="col-12">
            @if($editArea == 'addIngredient')
                <livewire:sub-component.add-ingredient :recipe="$recipe" />
            @elseif($editArea == 'editItem')
                <x-card title="Edit {{ $editingRecipeItem->ingredient->name }}" wire:key="editItem">
                    <form wire:submit.prevent="updateItem">
                        <div class="row">
                            <x-form.text-input name="editQuantityInput" label-name="Quantity" cols="5"/>
                            <x-form.select-units wire:model="editUnitInput" cols="5" wire:key="editRecipeItem"/>
                            <div class="col-md-2">
                                <x-form.toggle-switch wire:model="editCleanedInput" label-name="Cleaned" />
                                <x-form.toggle-switch wire:model="editCookedInput" label-name="Cooked" />
                            </div>
                        </div>
                        <x-ls.submit-button targets="updateItem" />
                    </form>
                </x-card>
            @endif
        </div>
    </div>

    <livewire:recipe-update :recipe="$recipe"/>
</div>
