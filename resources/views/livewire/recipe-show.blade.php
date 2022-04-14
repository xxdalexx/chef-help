<?php
/** @var \App\Models\Recipe $recipe */
?>
<div>
    <div class="display-1 text-center mb-5">{{ $recipe->name }}</div>

    <div class="row">
        <div class="col-md-6">
            <x-card title="Ingredients">
                <x-livewire.recipe-show.ingredient-table :recipe="$recipe" />
            </x-card>
        </div>

        <div class="col-md-6">

            <x-card title="Recipe Stats" header-class="text-end">
                <x-livewire.recipe-show.stats :recipe="$recipe" />
            </x-card>

        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            @if($editArea == 'recipe')
                <x-card title="Editing Recipe" >
                    <x-form.group.recipe />
                    <x-button.block wire:click="updateRecipe" style-type="success" text="Save" />
                </x-card>
            @elseif($editArea == 'ingredient')
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
            @endif
        </div>
    </div>
</div>