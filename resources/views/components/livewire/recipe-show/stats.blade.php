<?php
/** @var \App\Models\Recipe $recipe */
?>
<table class="table">
    <tbody>
        <tr>
            <td>
                Total Cost:
            </td>
            <td class="text-end">
                {{ $recipe->getTotalCostAsString() }}
            </td>
        </tr>
        <tr>
            <td>
                Portions
            </td>
            <td class="text-end">
                {{ $recipe->portions }}
            </td>
        </tr>
        <tr>
            <td>
                Cost Per Portion:
            </td>
            <td class="text-end">
                {{ $recipe->getCostPerPortionAsString() }}
            </td>
        </tr>
        <tr>
            <td>
                Menu Price
            </td>
            <td class="text-end">
                {{ $recipe->getPriceAsString() }}
            </td>
        </tr>
        <tr>
            <td>
                Menu Cost Percentage
            </td>
            <td class="text-end">
                {{ $recipe->getPortionCostPercentageAsString() }}
            </td>
        </tr>
    </tbody>
</table>

<x-button.block wire:click="$set('editArea', 'recipe')" text="Edit" style-type="warning" />
