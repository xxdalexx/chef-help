<?php
/** @var \App\Models\Ingredient $ingredient */
?>
<div>
    <div class="display-1 text-center mb-5">{{ $ingredient->name }}</div>

    <div class="row">
        <div class="col-md-8">
            <x-card title="Details">
                <x-table>
                    <tr>
                        <td>Cleaned Yield:</td>
                        <td>{{ $ingredient->cleaned_yield }}%</td>
                    </tr>
                    <tr>
                        <td>Cooked Yield:</td>
                        <td>{{ $ingredient->cooked_yield }}%</td>
                    </tr>
                    <tr>
                        <td>As Purchased:</td>
                        <td>
                            @if($hasAsPurchased)
                                {{ $ingredient->asPurchased->getPriceAsString() }} per
                                {{ $ingredient->asPurchased->quantity }} {{ $ingredient->asPurchased->unit->value }}
                            @else
                                No Data
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Recipes Used In
                        </td>
                        <td>
                            {{ $ingredient->recipe_items_count }}
                        </td>
                    </tr>
                </x-table>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card title="Edit">
                <form wire:submit.prevent="editIngredient">
                    <x-form.text-input name="nameInput" label-name="Name" />
                    <x-form.text-input name="cleanedYieldInput" label-name="Cleaned Yield %" />
                    <x-form.text-input name="cookedYieldInput" label-name="Cooked Yield %" />
                    <x-ls.submit-button targets="editIngredient" />
                </form>
            </x-card>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <x-card card-class="mt-2" title="Update As Purchased Pricing">
                <form wire:submit.prevent="addAsPurchased">
                    <x-form.text-input name="apQuantityInput" label-name="Quantity" />
                    <x-form.select-units wire:model="apUnitInput" />
                    <x-form.price-input name="apPriceInput" label-name="Price" />
                    <x-ls.submit-button targets="addAsPurchased" />
                </form>
            </x-card>
        </div>
        <div class="col-md-8">
            <x-card card-class="mt-2" title="Price History">
                @if($ingredient->asPurchasedAll->count())
                    <x-table>
                        <x-slot name="heading">
                            <th>Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Date</th>
                            <th class="text-end">Variance</th>
                        </x-slot>
                        @foreach($ingredient->asPurchasedAll as $apRecord)
                            <tr>
                                <td>
                                    {{ $apRecord->getPriceAsString() }}
                                </td>
                                <td class="text-center">
                                    {{ $apRecord->quantity }} {{ $apRecord->unit->value }}
                                </td>
                                <td class="text-center">
                                    {{ $apRecord->created_at }}
                                </td>
                                <td class="text-end">
                                    ?
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                @else
                    No Records
                @endif
            </x-card>
        </div>

    </div>





</div>
