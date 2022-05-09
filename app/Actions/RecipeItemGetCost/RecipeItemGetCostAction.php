<?php

namespace App\Actions\RecipeItemGetCost;

use App\Actions\RecipeItemGetCost\Steps\BothUseEachTypeAsUnit;
use App\Actions\RecipeItemGetCost\Steps\UnitTypeConversion;
use App\Actions\RecipeItemGetCost\Steps\UnitSystemConversion;
use App\Actions\RecipeItemGetCost\Steps\CalculateCookedAndCleaned;
use App\Actions\RecipeItemGetCost\Steps\ConvertToRecipeItemUnitAndQuantity;
use App\Actions\RecipeItemGetCost\Steps\ReturnFinalCost;
use App\Actions\RecipeItemGetCost\Steps\UsingRecipeAsIngredientAndRecipeItemUnitIsPortion;
use App\Models\RecipeItem;
use Brick\Money\Money;
use Illuminate\Pipeline\Pipeline as Ladder;

class RecipeItemGetCostAction
{
    protected array $steps = [
        UsingRecipeAsIngredientAndRecipeItemUnitIsPortion::class, // Early Return
        BothUseEachTypeAsUnit::class, // Early Return
        UnitTypeConversion::class,
        UnitSystemConversion::class,
        ConvertToRecipeItemUnitAndQuantity::class,
        CalculateCookedAndCleaned::class,
        ReturnFinalCost::class
    ];

    protected RecipeItemGetCostStruct $struct;

    public function __construct(RecipeItem $recipeItem)
    {
        $this->struct = new RecipeItemGetCostStruct($recipeItem);
    }

    public function execute(): Money
    {
        return app(Ladder::class)
            ->send($this->struct)
            ->through($this->steps)
            ->thenReturn();
    }

    public function setSteps(array $steps): self
    {
        $this->steps = $steps;
        return $this;
    }
}
