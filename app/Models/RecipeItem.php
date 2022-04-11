<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeItem extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit' => MeasurementEnumCast::class,
        'cleaned' => 'boolean',
        'cooked' => 'boolean'
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
        $price = $this->ingredient->asPurchased->getCostPerBaseUnit()
            ->multipliedBy($this->quantity, RoundingMode::HALF_UP)
            ->multipliedBy($this->unit->conversionFactor(), RoundingMode::HALF_UP);

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

}
