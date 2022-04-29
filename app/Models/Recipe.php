<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MoneyCast;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Recipe extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class,
        'portions' => BigDecimalCast::class
    ];

    public Collection $ingredientList;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    //Fake a has many through relationship. RecipeItem contains foreign keys for both Recipe and Ingredient
    public function getIngredientsAttribute(): Collection
    {
        if (! empty($this->ingredientList)) return $this->ingredientList;

        if (! $this->relationLoaded('items.ingredient.asPurchased') ) {
            $this->load('items.ingredient.asPurchased');
        }

        return $this->ingredientList = $this->items->map(function ($item) {
            return $item->ingredient;
        });
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
        return (string) (float) (string) $this->getPortionCostPercentage()->multipliedBy(100) . '%';
    }

    public function getPriceAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->price, $withDollarSign);
    }

    public function getCostingPercentageDifferenceFromGoalAsString(): string
    {
        return (string) $this->getCostingPercentageDifferenceFromGoal();
    }

    public function getMinPriceForCostingGoalAsString(): string
    {
        return moneyToString($this->getMinPriceForCostingGoal());
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

    public function hasInaccurateCost(): bool
    {
        foreach ($this->items as $item) {
            if (! $item->canCalculateCost()) return true;
        }
        return false;
    }

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
        if ($this->portions) {
            return $this->getTotalCost()->dividedBy($this->portions, RoundingMode::UP);
        }
        return money(0);
    }

    public function getPortionCostPercentage(): BigDecimal
    {
        $costPerPortion = $this->getCostPerPortion()->getAmount();
        $price = $this->price->getAmount();

        return $costPerPortion->dividedBy($price, 3,RoundingMode::UP);
    }

    public function getCostingPercentageDifferenceFromGoal(): BigDecimal
    {
        $current = $this->getPortionCostPercentage()->multipliedBy(100);
        $goal = $this->menuCategory->costing_goal;
        return $current->minus($goal)->toScale(1, RoundingMode::HALF_UP);
    }

    public function getMinPriceForCostingGoal(): Money
    {
        return $this->getCostPerPortion()
            ->dividedBy($this->menuCategory->costing_goal, RoundingMode::HALF_UP)
            ->multipliedBy('100');
    }
}
