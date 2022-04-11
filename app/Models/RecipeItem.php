<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeItem extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit' => MeasurementEnumCast::class
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
        return $this->ingredient->asPurchased->getCostPerBaseUnit()
            ->multipliedBy($this->quantity)
            ->multipliedBy($this->unit->conversionFactor());
    }

    public function getCostAsString(bool $withDollarSign = true)
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

}
