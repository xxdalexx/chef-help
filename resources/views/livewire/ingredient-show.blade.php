<?php
/**
 * @var \App\Models\Ingredient $ingredient
 * @var \App\Models\Location   $location
 */
?>
<div>
    <div class="display-1 text-center mb-5">{{ $ingredient->name }}</div>

    <div class="row" wire:key="details">
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
                    <x-form.text-input name="nameInput" label-name="Name"/>
                    <x-form.text-input name="cleanedYieldInput" label-name="Cleaned Yield %"/>
                    <x-form.text-input name="cookedYieldInput" label-name="Cooked Yield %"/>
                    <x-ls.submit-button targets="editIngredient"/>
                </form>
            </x-card>
        </div>
    </div>

    <div class="row" wire:key="as-purchased">
        <div class="col-md-4">
            <x-card card-class="mt-2" title="Update As Purchased Pricing">
                <form wire:submit.prevent="addAsPurchased">
                    <x-form.text-input name="apQuantityInput" label-name="Quantity"/>
                    <x-form.select-units wire:model="apUnitInput"/>
                    <x-form.price-input name="apPriceInput" label-name="Price"/>
                    <x-ls.submit-button targets="addAsPurchased"/>
                </form>
            </x-card>
        </div>
        <div class="col-md-8">
            <x-card card-class="mt-2" title="Price History">
                @if($ingredient->asPurchasedAll->count())
                    <x-table>
                        <x-slot name="heading">
                            <th>Price</th>
                            <th class="text-center">Base Unit Price</th>
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
                                    {{ moneyToString( $apRecord->getCostPerBaseUnit(), false, 4 ) }} / {{ $apRecord->getBaseUnit()->value }}
                                </td>
                                <td class="text-center">
                                    {{ $apRecord->quantity }} {{ $apRecord->unit->value }}
                                </td>
                                <td class="text-center">
                                    {{ $apRecord->created_at->toFormattedDateString() }} ({{ $apRecord->created_at->diffForHumans() }})
                                </td>
                                <td class="text-end">
                                    @if($apRecord->previousCostPerBaseUnit)
                                        <x-costing-goal-difference :number="$apRecord->getVariancePercentageAsString()"/>
                                    @endif
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

    <div class="row" wire:key="inventory-locations">
        <div class="col-md-8">
            <x-card card-class="mt-2" title="Inventory Locations">
                <ul class="list-group">
                    @forelse($ingredient->locations as $location)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $location->name }}
                            <span>
                                <i wire:click="removeLocation({{ $location->id }})" class="bi bi-trash text-danger cursor-pointer"></i>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">No Locations Set</li>
                    @endforelse
                </ul>
            </x-card>
        </div>
        <div class="col-md-4">
            <x-card card-class="mt-2" title="Add Location" edit-click="$emit('showModal', 'locationsModal')">
                <form wire:submit.prevent="addLocation">
                    <x-form.select-location wire:model="locationInput" :ingredient="$ingredient" />
                    <x-ls.submit-button targets="addLocation"/>
                </form>
            </x-card>
        </div>
    </div>

    <div class="row" wire:key="cross-measurements">
        <div class="col">
            <x-card card-class="mt-2" title="Measurement System Conversions">
                <ul class="list-group">
                    @forelse($ingredient->crossConversions as $conversion)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $conversion->getFirstMeasurementAsString()  }} = {{ $conversion->getSecondMeasurementAsString() }}
                            <span>
                                <i wire:click="removeCrossConversion({{ $conversion->id }})" class="bi bi-trash text-danger cursor-pointer"></i>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">No Conversions Set</li>
                    @endforelse
                </ul>
                <x-button.block text="Add" wire:click="$emit('showModal', 'crossConversionCreateModal')" />
            </x-card>
        </div>
    </div>

    <livewire:sub-component.locations-management-modal/>
    <livewire:cross-conversion-create :ingredient="$ingredient"/>

</div>
