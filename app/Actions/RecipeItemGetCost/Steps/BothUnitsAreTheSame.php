<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use Brick\Money\Money;
use Closure;

class BothUnitsAreTheSame
{
    /*
    |--------------------------------------------------------------------------
    | When both the RecipeItem and Ingredient are using the same unit of
    | measurement, early out return the simple calculation instead of
    | needlessly running through the rest of the steps.
    |--------------------------------------------------------------------------
    */

    public function handle(RecipeItemGetCostStruct $data, Closure $next): Money|Closure
    {
        if ($this->unitsAreSame($data)) {
            return $data->recipeItem->ingredient->getCostPerCostingBaseUnit()
                ->multipliedBy( $data->recipeItem->quantity)
                ->multipliedBy( $data->recipeItem->ingredient->costingUnit()->conversionFactor() );
        }

        return $next($data);
    }

    protected function unitsAreSame(RecipeItemGetCostStruct $data): bool
    {
        $recipeItemUnit = $data->recipeItem->unit;
        $ingredientUnit = $data->recipeItem->ingredient->costingUnit();

        return $recipeItemUnit->isSameAs($ingredientUnit);
    }
}
