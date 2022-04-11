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

    public function scopeWithAllRelations($query): Builder
    {
        return $query->with('items.ingredient.asPurchased');
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

    public function totalCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getTotalCost(), $withDollarSign);
    }

    public function costPerPortionAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCostPerPortion(), $withDollarSign);
    }

    public function portionCostPercentageAsString(): string
    {
        return (string) (float) (string) $this->getPortionCostPercentage()->multipliedBy(100);
    }

}
