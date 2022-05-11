<?php

namespace App\Contracts;

use App\Measurements\MeasurementEnum;
use App\Models\CrossConversion;
use Brick\Math\BigDecimal;
use Brick\Money\Money;

interface CostableIngredient
{
    public function canCrossConvert(array $neededConversion, array $eachToEachValues = null): bool;

    public function costingUnit(): MeasurementEnum;

    public function getCostPerCostingBaseUnit(): Money;

    public function getCostingBaseUnit(): MeasurementEnum;

    public function getCrossConversion(array $neededConversion, array $eachToEachValues = null): CrossConversion;

    public function cleanedYieldDecimal(): BigDecimal;

    public function cookedYieldDecimal(): BigDecimal;

}
