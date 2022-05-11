<?php

namespace App\Contracts;

use App\Measurements\MeasurementEnum;
use App\Models\CrossConversion;
use Brick\Math\BigDecimal;
use Brick\Money\Money;

interface CostableIngredient
{
    public function canCrossConvert(array $neededConversion): bool;

    public function costingUnit(): MeasurementEnum;

    public function getCostPerCostingBaseUnit(): Money;

    public function getCostingBaseUnit(): MeasurementEnum;

    public function getCrossConversion(array $neededConversion): CrossConversion;

    public function cleanedYieldDecimal(): BigDecimal;

    public function cookedYieldDecimal(): BigDecimal;

}
