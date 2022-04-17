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

    <div class="row mt-4">
        <div class="col-12">
            @if($editArea == 'recipe')
                <x-card title="Editing Recipe" >
                    <x-form.group.recipe />
                    <x-button.block wire:click="updateRecipe" style-type="success" text="Save" />
                </x-card>
            @elseif($editArea == 'ingredient')
                <livewire:sub-component.add-ingredient :recipe="$recipe" />
            @endif
        </div>
    </div>
</div>
