<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\UsVolume;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property MeasurementEnum $unit
 */
class RecipeItem extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit'    => MeasurementEnumCast::class,
        'cleaned' => 'boolean',
        'cooked'  => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithFullIngredientRelation($query): Builder
    {
        return $query->with('ingredient.asPurchased');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function getCost(): Money
    {
        // Weight/Volume Check
        if ($this->unit->getType() != $this->ingredient->asPurchased->unit->getType()) {
            throw new \Exception('Weight/Volume Conversion Attempted');
        }

        //$20 per liter, or $20 per 33.81413 floz
        $costPerApBaseUnit = $this->ingredient->asPurchased->getCostPerBaseUnit();

        //Until a Misc System is created, we can assume only US<->Metric will hit here.
        if ($this->unit->getSystem() != $this->ingredient->asPurchased->unit->getSystem()) {
            $apBaseUnit = $this->ingredient->asPurchased->getBaseUnit();

            $costPerApBaseUnit = match ($apBaseUnit) {
                MetricVolume::liter => $this->asPurchasedCostFromLitersToFloz(),
                UsVolume::floz      => $this->asPurchasedCostFromFlozToLiters(),
                default             => throw new \Exception('US<->Metric Conversion Case Missing.')
            };
        }


        $price = $costPerApBaseUnit
            ->multipliedBy($this->unit->conversionFactor(), RoundingMode::HALF_UP)
            ->multipliedBy($this->quantity, RoundingMode::HALF_UP);


        if ($this->cleaned) {
            $price = $price->dividedBy($this->ingredient->cleanedYieldDecimal(), RoundingMode::HALF_UP);
        }

        if ($this->cooked) {
            $price = $price->dividedBy($this->ingredient->cookedYieldDecimal(), RoundingMode::HALF_UP);
        }

        return $price;
    }

    public function getCostAsString(bool $withDollarSign = true)
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

    /*
    |--------------------------------------------------------------------------
    | Conversion Methods
    |--------------------------------------------------------------------------
    */

    public function asPurchasedCostFromFlozToLiters()
    {
        // 1floz = 0.02957344 L
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('0.02957344', RoundingMode::HALF_UP);
    }

    public function asPurchasedCostFromLitersToFloz()
    {
        // 1 Liter = 33.81413 floz
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('33.81413', RoundingMode::HALF_UP);
    }

}
