<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use Brick\Money\Money;
use Closure;

class ConvertToRecipeItemUnitAndQuantity
{
    public function handle(RecipeItemGetCostStruct $data, $next): Money|Closure
    {
        $data->workingCost = $data->workingCost
            ->multipliedBy( $data->recipeItem->unit->conversionFactor(), 5)
            ->multipliedBy( $data->recipeItem->quantity, 5);

        return $next($data);
    }
}
