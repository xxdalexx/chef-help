<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MoneyCast;
use App\Contracts\CostableIngredient;
use App\Measurements\MeasurementEnum;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Recipe extends BaseModel implements CostableIngredient
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class,
        'portions' => BigDecimalCast::class,
        'costing_goal' => BigDecimalCast::class
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

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_items');
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

        if ($price->isEqualTo(0)) {
            return $price; // Return 0
        }

        return $costPerPortion->dividedBy($price, 3,RoundingMode::UP);
    }

    public function getCostingPercentageDifferenceFromGoal(): BigDecimal
    {
        $current = $this->getPortionCostPercentage()->multipliedBy(100);

        $goal = $this->getCostingGoal();

        return $current->minus($goal)->toScale(1, RoundingMode::HALF_UP);
    }

    public function getMinPriceForCostingGoal(): Money
    {
        if (! $this->hasCostingGoal() ) {
            return money();
        }

        return $this->getCostPerPortion()
            ->dividedBy($this->getCostingGoal(), RoundingMode::HALF_UP)
            ->multipliedBy('100');
    }

    public function getCostingGoal(): BigDecimal
    {
        if (! $this->hasCostingGoal()) {
            return BigDecimal::of(0);
        }

        return $this->costing_goal->isGreaterThan(0)
            ? $this->costing_goal
            : $this->menuCategory->costing_goal;
    }

    public function hasCostingGoal(): bool
    {
        return $this->costing_goal->isGreaterThan(0) || $this->menuCategory?->costing_goal->isGreaterThan(0);
    }

    public function hasPrice(): bool
    {
        return $this->price->isGreaterThan(0);
    }

    public function canCalculateMenuCostPercentage(): bool
    {
        return $this->hasCostingGoal() && $this->hasPrice();
    }

    /*
    |--------------------------------------------------------------------------
    | CostableIngredient Methods
    |--------------------------------------------------------------------------
    */

    public function canCrossConvert(array $neededConversion): bool
    {
        return true;
    }

    public function costingUnit(): MeasurementEnum
    {
        return UsWeight::oz;
    }

    public function getCostPerCostingBaseUnit(): Money
    {
        return money(0);
    }

    public function getCostingBaseUnit(): MeasurementEnum
    {
        return UsWeight::oz;
    }

    public function getCrossConversion(array $neededConversion): CrossConversion
    {
        return CrossConversion::make([
            'quantity_one' => 1,
            'unit_one' => UsWeight::oz,
            'quantity_two' => 1,
            'unit_two' => UsVolume::floz
        ]);
    }

    public function cleanedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of(1);
    }

    public function cookedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of(1);
    }


}
