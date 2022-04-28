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
                Menu Category:
            </td>
            <td class="text-end">
                @if(empty($recipe->menuCategory))
                    Not Assigned
                @else
                    {{ $recipe->menuCategory->name }}
                @endif
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
                Minimum Price That Meets Goal:
            </td>
            <td class="text-end">
                {{ $recipe->getMinPriceForCostingGoalAsString() }}
            </td>
        </tr>
        <tr>
            <td>
                Menu Cost Percentage
            </td>
            <td class="text-end">
                {{ $recipe->getPortionCostPercentageAsString() }}
                <x-costing-goal-difference :number="$recipe->getCostingPercentageDifferenceFromGoalAsString()"/>
            </td>
        </tr>
    </tbody>
</table>

<x-button.block wire:click="$emit('showModal', 'updateRecipeModal')" text="Edit" style-type="warning" :show-spinner="false"/>
