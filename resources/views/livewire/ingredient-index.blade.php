<?php
/** @var \App\Models\Ingredient $ingredient */
/** @var \Illuminate\Database\Eloquent\Collection $ingredients */
?>

<div>
    <div class="display-1 text-center mb-5">Ingredients</div>

    <x-livewire.search-box />

    <x-table>
        <x-slot:heading>
            <th scope="col">Name</th>
        </x-slot:heading>

        @foreach($ingredients as $ingredient)
            <tr>
                <td>
                    <a href="{{ $ingredient->showLink() }}">{{ $ingredient->name }}</a>
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-pagination-links :paginated="$ingredients"/>

    <hr>

    <div class="mb-5">
    @if($showCreateForm)
        <x-card title="Create Ingredient">
            <div class="row">
                <x-form.text-input name="nameInput" label-name="Name" cols="8" />
                <x-form.text-input name="cleanedInput" label-name="Cleaned Yield %" />
                <x-form.text-input name="cookedInput" label-name="Cooked Yield %" />
            </div>

            @if($createAsPurchase)
                <div class="row">
                    <x-form.text-input name="apQuantityInput" label-name="Quantity" />
                    <x-form.select-units wire:model="apUnitInput" />
                    <x-form.text-input name="apPriceInput" label-name="Price" />
                </div>
            @else
                Something to toggle.
            @endif

            <x-button.block wire:click="createIngredient" style-type="success" text="Create" />
        </x-card>
    @else
        <x-button.block wire:click="$toggle('showCreateForm')" text="Create New Ingredient" />
    @endif
    </div>
</div>
