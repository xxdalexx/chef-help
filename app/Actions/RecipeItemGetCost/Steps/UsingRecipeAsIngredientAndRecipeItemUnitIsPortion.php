<?php

namespace App\Actions\RecipeItemGetCost\Steps;

use App\Actions\RecipeItemGetCost\RecipeItemGetCostStruct;
use App\Models\Recipe;
use Brick\Money\Money;
use Closure;

class UsingRecipeAsIngredientAndRecipeItemUnitIsPortion
{
    /*
    |--------------------------------------------------------------------------
    | In the case where a Recipe is used as an ingredient on RecipeItem, and
    | the RecipeItem Unit is portion, we can return the Recipe's cost per
    | portion multiplied by the RecipeItem's quantity of portions.
    |--------------------------------------------------------------------------
    */

    public function handle(RecipeItemGetCostStruct $data, Closure $next): Money|Closure
    {
        if ( $data->recipeItem->ingredient_type == Recipe::class && $data->recipeItem->unit->value == 'portion' ) {
            return $data->recipeItem->ingredient->getCostPerPortion()->multipliedBy( $data->recipeItem->quantity );
        }

        return $next($data);
    }
}
