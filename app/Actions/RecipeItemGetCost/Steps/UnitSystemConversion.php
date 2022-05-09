<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Closure;

class UnitSystemConversion
{
    public function handle(RecipeItemGetCostStruct $data, $next): Money|Closure
    {
        $recipeItem = $data->recipeItem;
        if ($recipeItem->unit->getSystem() == $data->currentUnit->getSystem()) return $next($data);


        $conversion = match ($data->currentUnit) {
            MetricVolume::liter => '33.81413',  // 1 Liter = 33.81413 floz
            UsVolume::floz => '0.02957344',     // 1 floz = 0.02957344 L
            MetricWeight::gram => '0.03527396', // 1 gram = 0.03527396 oz
            UsWeight::oz => '28.34952',         // 1 oz = 28.34952 gram
            default => throw new \Exception('Conversion is not US<->Metric')
        };

        $data->workingCost = $data->workingCost->dividedBy($conversion, RoundingMode::HALF_UP);

        return $next($data);
    }
}
