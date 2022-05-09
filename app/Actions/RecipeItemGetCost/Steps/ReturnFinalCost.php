<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use Brick\Money\Money;

class ReturnFinalCost
{
    public function handle(RecipeItemGetCostStruct $data): Money
    {
        return $data->workingCost;
    }
}
