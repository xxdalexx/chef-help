<?php
/** @var \App\Models\Ingredient $ingredient */
?>
<div>
    <div class="display-1 text-center mb-5">{{ $ingredient->name }}</div>

    <x-card title="Details">
        <x-table>
            <tr>
                <td>Cleaned Yield</td>
                <td>{{ $ingredient->cleaned_yield }}</td>
            </tr>
            <tr>
                <td>Cooked Yield</td>
                <td>{{ $ingredient->cooked_yield }}</td>
            </tr>
            <tr>
                <td>As Purchased</td>
                <td>
                    @if($hasAsPurchased)
                        {{ $ingredient->asPurchased->getPriceAsString() }} per
                        {{ $ingredient->asPurchased->quantity }} {{ $ingredient->asPurchased->unit->value }}
                    @else
                        No Data
                    @endif
                </td>
            </tr>
        </x-table>
    </x-card>

    <x-card card-class="mt-2" title="Edit">
        <div class="row">
            <div class="row">
                <x-form.text-input name="nameInput" label-name="Name" cols="8" />
                <x-form.text-input name="cleanedYieldInput" label-name="Cleaned Yield %" />
                <x-form.text-input name="cookedYieldInput" label-name="Cooked Yield %" />
            </div>
        </div>
        <x-button.block wire:click="editIngredient" style-type="success" text="Save" />
    </x-card>

    <x-card card-class="mt-2" title="Update As Purchased Pricing">
        <div class="row">
            <x-form.text-input name="apQuantityInput" label-name="Quantity" />
            <x-form.select-units wire:model="apUnitInput" />
            <x-form.price-input name="apPriceInput" label-name="Price" />
        </div>
        <x-button.block wire:click="addAsPurchased" style-type="success" text="Save" />
    </x-card>

    <x-card card-class="mt-2" title="Price History">
        @if($ingredient->asPurchasedAll->count())
            <x-table>
                <x-slot name="heading">
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </x-slot>
                @foreach($ingredient->asPurchasedAll as $apRecord)
                    <tr>
                        <td>
                            {{ $apRecord->getPriceAsString() }}
                        </td>
                        <td>
                            {{ $apRecord->quantity }} {{ $apRecord->unit->value }}
                        </td>
                        <td>
                            {{ $apRecord->created_at }}
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @else
            No Records
        @endif
    </x-card>

</div>
