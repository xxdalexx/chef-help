<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * @property MeasurementEnum $unit
 */
class RecipeItem extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit'     => MeasurementEnumCast::class,
        'cleaned'  => 'boolean',
        'cooked'   => 'boolean',
        'quantity' => BigDecimalCast::class
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
    | Attribute Accessors
    |--------------------------------------------------------------------------
    */

    public function getIngredientNameAttribute(): string
    {
        //Lazy loading issues with livewire?
        if (! $this->relationLoaded('ingredient') ) {
            $this->load('ingredient.asPurchased');
        }

        return $this->ingredient->name;
    }

    public function getMeasurementAttribute(): string
    {
        return $this->quantity . ' ' . $this->unit->value;
    }

    public function getCostAttribute(): string
    {
        return Cache::remember($this->costCacheKey(), $thirtyDays = 60 * 60 * 24 * 30, function () {
            return $this->getCostAsString();
        });
    }

    public function costCacheKey(): string
    {
        return 'RecipeItemCost' . $this->id . $this->updated_at;
    }

    public function canNotCalculateCostReason(): string
    {
        // Ingredient Missing As Purchased Record
        if (! $this->ingredient->asPurchased) {
            return 'No As Purchased Data';
        }

        // Weight/Volume Check
        if ($this->unit->getType() != $this->ingredient->asPurchased->unit->getType()) {
            return 'No Weight <-> Volume Conversion';
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function canCalculateCost(): bool
    {
        // Ingredient Missing As Purchased Record
        if (! $this->ingredient->asPurchased) return false;

        // Weight/Volume Check
        if ($this->unit->getType() != $this->ingredient->asPurchased->unit->getType()) {
            return false;
        }

        return true;
    }

    public function getCost(): Money
    {
        if (! $this->canCalculateCost() ) return money(0);

        //$20 per liter, or $20 per 33.81413 floz
        $costPerApBaseUnit = $this->ingredient->asPurchased->getCostPerBaseUnit();

        //Until a Misc System is created, we can assume only US<->Metric will hit here.
        if ($this->unit->getSystem() != $this->ingredient->asPurchased->unit->getSystem()) {
            $apBaseUnit = $this->ingredient->asPurchased->getBaseUnit();

            $costPerApBaseUnit = match ($apBaseUnit) {
                MetricVolume::liter => $this->asPurchasedCostFromLitersToFloz(),
                UsVolume::floz => $this->asPurchasedCostFromFlozToLiters(),
                MetricWeight::gram => $this->asPurchasedCostFromGramsToOz(),
                UsWeight::oz => $this->asPurchasedCostFromOzToGrams(),
                default => throw new \Exception('US<->Metric Conversion Case Missing.')
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

    public function getCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

    /*
    |--------------------------------------------------------------------------
    | Conversion Methods
    |--------------------------------------------------------------------------
    */

    protected function asPurchasedCostFromFlozToLiters(): Money
    {
        // 1 floz = 0.02957344 L
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('0.02957344', RoundingMode::HALF_UP);
    }

    protected function asPurchasedCostFromLitersToFloz(): Money
    {
        // 1 Liter = 33.81413 floz
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('33.81413', RoundingMode::HALF_UP);
    }

    protected function asPurchasedCostFromOzToGrams(): Money
    {
        // 1 oz = 28.34952 gram
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('28.34952', RoundingMode::HALF_UP);
    }

    protected function asPurchasedCostFromGramsToOz(): Money
    {
        // 1 gram = 0.03527396 oz
        return $this->ingredient
            ->asPurchased
            ->getCostPerBaseUnit()
            ->dividedBy('0.03527396', RoundingMode::HALF_UP);
    }
}
