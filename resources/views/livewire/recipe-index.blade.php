<?php
/** @var \App\Models\Recipe $recipe */
?>

<div>
    <div class="display-1 text-center mb-5">Recipes</div>

    <x-livewire.search-box />

    <x-table>
        <x-slot:heading>
            <th scope="col" style="width: 33%;">Name</th>
            <th scope="col" class="text-center" style="width: 33%;">Menu Price</th>
            <th scope="col" class="text-end" style="width: 33%;">Current Cost %</th>
        </x-slot:heading>

        @foreach($recipes as $recipe)
            <tr>
                <td>
                    <a href="{{ $recipe->showLink() }}">{{ $recipe->name }}</a>
                </td>
                <td class="text-center">
                    {{ $recipe->getPriceAsString() }}
                </td>
                <td class="text-end">
                    {{ $recipe->getPortionCostPercentageAsString() }}%
                </td>
            </tr>
        @endforeach
    </x-table>

    <x-pagination-links :paginated="$recipes" />

    <hr>

    <div class="mb-5">
    @if($showCreateForm)

        <x-card title="Editing Recipe" >
            <x-form.group.recipe />
            <x-button.block wire:click="createRecipe" style-type="success" text="Create" />
        </x-card>

    @else
        <x-button.block wire:click="$toggle('showCreateForm')" text="Create New Recipe" />
    @endif
    </div>

</div>
