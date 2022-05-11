<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\EachMeasurement;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Closure;

class UnitTypeConversion
{
    public function handle(RecipeItemGetCostStruct $data, Closure $next): Closure|Money
    {
        $recipeItem = $data->recipeItem;
        if (!$recipeItem->crossConversionNeeded()) return $next($data);

        // Early out conversion when both units are an EachMeasurement Model with different names.
        if ($recipeItem->crossConversionTypeNeeded() == [EachMeasurement::class, EachMeasurement::class]) {
            return $this->bothEachButDifferentValues($data);
        }

        // SYSTEMS ARE THE SAME, FIRST IF STATEMENT SKIPPED/EVALUATES FALSE
        // Paper Math, with a conversion of 128 grams = 1 cup, this is where we would convert
        // $0.0065/gram -> $0.832/128 gram -> $0.832/1 Cup -> $0.104/floz
        // So we need to manually override and convert the ap from grams to floz before it does the system check.
        // $data->workingCost = .104
        // $currentUnit = UsVolume::floz

        // SYSTEMS ARE DIFFERENT, FIRST IF EVALUATES TRUE
        // What's going to happen if we do our conversion with US Weight instead of Metric Weight?
        // Using a conversion of 4.5oz = 1 cup, now the paper conversion is:
        // $0.0065/gram -> $0.184272/oz -> $0.829224/4.5oz -> $0.829224/cup -> $0.104/floz
        // Still need the same override/conversion as before, but now we need to account for the extra step.

        // WHEN THE CONVERSION USES EachMeasurement MODEL INSTEAD OF HARDCODED ENUM, SECOND IF EVALUATES TRUE
        // Using "shrimp" test with a conversion of 1 lb = 10 each, conversion is $10/lb -> $1/each


        $conversion           = $recipeItem->ingredient->getCrossConversion();
        $convertingToUnitType = $recipeItem->unit->getType();

        // Are the systems different between CrossConversion unit and the AP unit
        if ($data->currentUnit->getSystem() != $conversion->baseUnitOf($data->currentUnit->getType())->getSystem()) {
            // Using above example (second paragraph), we're at $0.0065/gram and need to change it to $0.184272/oz
            $systemConversion  = match ($data->currentUnit) {
                MetricVolume::liter => '33.81413',  // 1 Liter = 33.81413 floz
                UsVolume::floz => '0.02957344',     // 1 floz = 0.02957344 L
                MetricWeight::gram => '0.03527396', // 1 gram = 0.03527396 oz
                UsWeight::oz => '28.34952',         // 1 oz = 28.34952 gram
                default => throw new \Exception('Conversion is not US<->Metric')
            };
            $data->workingCost = $data->workingCost->dividedBy($systemConversion, RoundingMode::HALF_UP);
        }

        //Third Scenario Hits Here
        if ($recipeItem->unit->getType() == 'each' && $conversion->containsEach()) { // I think the containsEach is redundant now, need more tests to verify.
            //$10/lb -> ?/oz -> $10/lb -> $10/10each
            //$costPerBaseUnit = $1
            $data->workingCost = $data->workingCost->multipliedBy($conversion->getNotEachUnit()->conversionFactor())
                ->dividedBy($conversion->getEachQuantity(), RoundingMode::HALF_UP);

            $data->currentUnit = $conversion->getEachUnit();
        }

        $factor = match ($convertingToUnitType) {
            'volume' => $conversion->weightToVolumeFactor(),
            'weight' => $conversion->volumeToWeightFactor(),
            'each' => 1
        };

        $data->workingCost = $data->workingCost->dividedBy($factor, RoundingMode::HALF_UP);
        $data->currentUnit = $conversion->baseUnitOf($convertingToUnitType);

        return $next($data);
    }

    protected function bothEachButDifferentValues(RecipeItemGetCostStruct $data): Money
    {
        $recipeItem = $data->recipeItem; // use 1 sprig
        $crossConversion = $recipeItem->ingredient->getCrossConversion(); // 1 bunch = 20 sprig
        $cost = $recipeItem->ingredient->getCostPerCostingBaseUnit(); // $10.00
        $unitName = $recipeItem->ingredient->getCostingBaseUnit()->name; // bunch

        return $cost->multipliedBy(
            $crossConversion->getEachMeasurementUnitQuantityByName( $unitName ) , 5
        )->dividedBy(
            $crossConversion->getEachMeasurementUnitQuantityByName( $recipeItem->unit->name ) , 5
        )->multipliedBy(
            $recipeItem->quantity
        );
    }

}
