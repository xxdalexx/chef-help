<?php
/** @var \App\Models\Recipe $recipe */
?>
<table class="table">
    <tbody>
        <tr>
            <td>
                Total Cost
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
                Cost Per Portion
            </td>
            <td class="text-end">
                {{ $recipe->getCostPerPortionAsString() }}
            </td>
        </tr>
        <tr>
            <td>
                Menu Category
            </td>
            <td class="text-end">
                @if(empty($recipe->menuCategory))
                    Not Assigned
                @else
                    {{ $recipe->menuCategory->name }} ({{ $recipe->menuCategory->costing_goal }}%)
                @endif
            </td>
        </tr>
        @if($recipe->costing_goal->isGreaterThan(0))
        <tr>
            <td>
                Costing Goal Override
            </td>
            <td class="text-end">
                {{ $recipe->costing_goal }}%
            </td>
        </tr>
        @endif
        <tr>
            <td>
                Menu Price
            </td>
            <td class="text-end">
                @if($recipe->hasPrice())
                    {{ $recipe->getPriceAsString() }}
                @else
                    Not Set
                @endif
            </td>
        </tr>
        @if($recipe->hasCostingGoal())
        <tr>
            <td>
                Minimum Price That Meets Goal
            </td>
            <td class="text-end">
                {{ $recipe->getMinPriceForCostingGoalAsString() }}
            </td>
        </tr>
        @endif
        @if($recipe->canCalculateMenuCostPercentage())
        <tr>
            <td>
                Menu Cost Percentage
            </td>
            <td class="text-end">
                {{ $recipe->getPortionCostPercentageAsString() }}
                <x-costing-goal-difference :number="$recipe->getCostingPercentageDifferenceFromGoalAsString()"/>
            </td>
        </tr>
        @endif
    </tbody>
</table>

<x-button.block wire:click="$emit('showModal', 'updateRecipeModal')" text="Edit" style-type="warning" :show-spinner="false"/>
