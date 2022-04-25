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
    @if($showCreateForm)
        <x-card title="Create Ingredient">
            <form wire:submit.prevent="createIngredient">
                <div class="row">
                    <x-form.text-input name="nameInput" label-name="Name" cols="8" />
                    <x-form.text-input name="cleanedInput" label-name="Cleaned Yield %" />
                    <x-form.text-input name="cookedInput" label-name="Cooked Yield %" />
                </div>

                <div class="d-flex justify-content-center mb-2">
                    <x-form.toggle-switch wire:model="createAsPurchase" label-name="Add As Purchased Pricing" />
                </div>

                @if($createAsPurchase)
                    <div class="row">
                        <x-form.text-input name="apQuantityInput" label-name="Quantity" />
                        <x-form.select-units wire:model="apUnitInput" />
                        <x-form.price-input name="apPriceInput" label-name="Price" />
                    </div>
                @endif
                <x-ls.submit-button targets="createIngredient" />
            </form>

        </x-card>
    @else
        <x-button.block wire:click="$toggle('showCreateForm')" text="Create New Ingredient" :show-spinner="false" />
    @endif
    </div>
</div>
