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

    public function crossConversionNeeded(): bool
    {
        return $this->unit->getType() != $this->ingredient->costingUnit()->getType();
    }

    public function canCalculateCost(): bool
    {
        if ($this->ingredient_type == Ingredient::class) {
            // Ingredient Missing As Purchased Record
            if (!$this->ingredient->asPurchased) return false;
        }

        if ($this->ingredient_type == Recipe::class) {
            // Recipe Checks
        }

        // Weight/Volume Check
        if ($this->crossConversionNeeded()) {
            return $this->ingredient->canConvertVolumeAndWeight();
        }

        return true;
    }

    public function getCost(): Money
    {
        if (! $this->canCalculateCost() ) return money(0);

        $action = new RecipeItemGetCostAction($this);
        return $action->execute();
    }

    public function getCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

}
