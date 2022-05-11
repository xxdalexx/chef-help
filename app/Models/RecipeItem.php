<?php

namespace App\Models;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostAction;
use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Contracts\CostableIngredient;
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
 * @property MeasurementEnum         $unit
 * @property-read CostableIngredient|Ingredient|Recipe $ingredient
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
        return $this->morphTo('ingredient');
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
        if (!$this->relationLoaded('ingredient')) {
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
        if (!$this->ingredient->asPurchased && $this->ingredient_type == Ingredient::class) {
            return 'No As Purchased Data';
        }

        // Weight/Volume Check
        if ($this->unit->getType() != $this->ingredient->costingUnit()->getType()) {
            return 'No Weight <-> Volume Conversion';
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    protected function neededCrossConversionIsEachToEach(): bool
    {
        $recipeItemUnit = $this->unit;
        $ingredientUnit = $this->ingredient->costingUnit();
        // When they are both EachMeasurement Models, check if the value is the same.
        return $recipeItemUnit instanceof EachMeasurement && $ingredientUnit instanceof EachMeasurement;
    }

    public function crossConversionNeeded(): bool
    {
        $recipeItemUnit = $this->unit;
        $ingredientUnit = $this->ingredient->costingUnit();
        // When they are both EachMeasurement Models, check if the value is the same.
        if ( $this->neededCrossConversionIsEachToEach() ) {
            return $recipeItemUnit->name != $ingredientUnit->name;
        }

        // Regular check
        return $recipeItemUnit->getType() != $ingredientUnit->getType();
    }

    public function crossConversionTypeNeeded(): array
    {
        if ( $this->crossConversionNeeded() ) {
            return [
                $this->ingredient->costingUnit()->getType(),
                $this->unit->getType()
            ];
        }
        return ['none', 'none']; // Garbage that won't match anything.
    }

    public function getCrossConversion(): CrossConversion
    {
        return $this->ingredient->getCrossConversion(
            $this->crossConversionTypeNeeded()
        );
    }

    public function canCalculateCost(): bool
    {
        if ($this->ingredient_type == Ingredient::class) {
            // Ingredient Missing As Purchased Record
            if (!$this->ingredient->asPurchased) return false;
        }

//        if ($this->ingredient_type == Recipe::class) {
//            // Recipe Checks
//        }

        // Unit Types Matching check
        if ($this->crossConversionNeeded()) {
            $eachToEach = null;
            if ( $this->neededCrossConversionIsEachToEach() ) {
                $eachToEach = [$this->unit->name, $this->ingredient->costingUnit()->name];
            }

            return $this->ingredient->canCrossConvert(
                $this->crossConversionTypeNeeded(),
                $eachToEach
            );
        }

        return true;
    }

    public function getCost(RecipeItemGetCostAction $action = null): Money
    {
        if (! $this->canCalculateCost() ) return money(0);

        if (empty($action)) {
            $action = new RecipeItemGetCostAction($this);
        }
        return $action->execute();
    }

    public function getCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

}
