<?php

namespace App\Actions\RecipeItemGetCost;

use App\Measurements\MeasurementEnum;
use App\Models\RecipeItem;
use Brick\Money\Money;

class RecipeItemGetCostStruct
{
    public Money $workingCost;

    public MeasurementEnum $currentUnit;

    public function __construct(
        public RecipeItem $recipeItem
    )
    {
        $this->workingCost = $recipeItem->ingredient->getCostPerCostingBaseUnit();
        $this->currentUnit = $recipeItem->ingredient->getCostingBaseUnit();
    }

}
