<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithAllRelations($query): Builder
    {
        return $query->with('items.ingredient.asPurchased');
    }

    public function scopeSearch($query, $searchString): Builder
    {
        return $query->where('name', 'LIKE', "%$searchString%");
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTotalCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getTotalCost(), $withDollarSign);
    }

    public function getCostPerPortionAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCostPerPortion(), $withDollarSign);
    }

    public function getPortionCostPercentageAsString(): string
    {
        return (string) (float) (string) $this->getPortionCostPercentage()->multipliedBy(100);
    }

    public function getTotalCostAttribute(): string
    {
        return $this->getTotalCostAsString();
    }

    public function getCostPerPortionAttribute(): string
    {
        return $this->getCostPerPortionAsString();
    }

    public function getPortionCostPercentageAttribute(): string
    {
        return $this->getPortionCostPercentageAsString();
    }

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    */

    public function showLink(): string
    {
        return route('recipe.show', $this);
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function getTotalCost(): Money
    {
        $totalCost = money(0);

        foreach ($this->items as $item) {
            $totalCost = $totalCost->plus($item->getCost());
        }

        return $totalCost;
    }

    public function getCostPerPortion(): Money
    {
        return $this->getTotalCost()->dividedBy($this->portions, RoundingMode::UP);
    }

    public function getPortionCostPercentage(): BigDecimal
    {
        $costPerPortion = $this->getCostPerPortion()->getAmount();
        $price = $this->price->getAmount();

        return $costPerPortion->dividedBy($price, 3,RoundingMode::UP);
    }



}
