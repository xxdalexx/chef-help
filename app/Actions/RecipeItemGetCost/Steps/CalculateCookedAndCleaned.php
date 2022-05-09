<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Closure;

class CalculateCookedAndCleaned
{
    public function handle(RecipeItemGetCostStruct $data, Closure $next): Closure|Money
    {
        if ($data->recipeItem->cleaned) {
            $data->workingCost = $data->workingCost->dividedBy( $data->recipeItem->ingredient->cleanedYieldDecimal(), RoundingMode::HALF_UP);
        }

        if ($data->recipeItem->cooked) {
            $data->workingCost = $data->workingCost->dividedBy( $data->recipeItem->ingredient->cookedYieldDecimal(), RoundingMode::HALF_UP);
        }

        return $next($data);
    }
}
