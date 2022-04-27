<?php
/** @var \App\Models\Ingredient $ingredient */
/** @var \Illuminate\Database\Eloquent\Collection $ingredients */
?>

<div>
    <div class="display-1 text-center mb-5">Ingredients</div>

    <x-livewire.search-box />

    <x-table>
        <x-slot:heading>
            <th scope="col"></th>
            <th scope="col" class="text-center">In Recipes</th>
            <th scope="col" class="text-end">Inventory Locations</th>
        </x-slot:heading>

        @foreach($ingredients as $ingredient)
            <tr>
                <td>
                    <a href="{{ $ingredient->showLink() }}">{{ $ingredient->name }}</a>
                </td>
                <td class="text-center">
                    {{ $ingredient->recipe_items_count }}
                </td>
                <td class="text-end">
                    {{ $ingredient->locations_count }}
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-pagination-links :paginated="$ingredients"/>

    <hr>

    <div class="mb-5">
        <x-button.block wire:click="$emit('showModal', 'createIngredientModal')" text="Create New Ingredient" :show-spinner="false" />
    </div>

    <livewire:ingredient-create/>
</div>
