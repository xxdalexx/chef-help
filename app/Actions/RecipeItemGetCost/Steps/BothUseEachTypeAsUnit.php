<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use App\Models\Recipe;
use Brick\Money\Money;
use Closure;

class BothUseEachTypeAsUnit
{
    public function handle(RecipeItemGetCostStruct $data, Closure $next): Money|Closure
    {
        if ($this->bothUseEach($data)) {
            return $data->recipeItem->ingredient->getCostPerCostingBaseUnit()->multipliedBy(
                $data->recipeItem->quantity
            );
        }

        return $next($data);
    }

    protected function bothUseEach(RecipeItemGetCostStruct $data): bool
    {
        return $data->recipeItem->unit->getType() == 'each' &&
               $data->recipeItem->ingredient->costingUnit()->getType() == 'each';
    }
}
