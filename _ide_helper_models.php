<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AsPurchased
 *
 * @property int $id
 * @property int $ingredient_id
 * @property \Brick\Math\BigDecimal $quantity
 * @property \App\Measurements\MeasurementEnum $unit
 * @property \Brick\Money\Money $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ingredient $ingredient
 * @method static \App\CustomCollections\AsPurchasedCollection|static[] all($columns = ['*'])
 * @method static \Database\Factories\AsPurchasedFactory factory(...$parameters)
 * @method static \App\CustomCollections\AsPurchasedCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased query()
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsPurchased whereUpdatedAt($value)
 */
	class AsPurchased extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CrossConversion
 *
 * @property int $id
 * @property int $ingredient_id
 * @property \Brick\Math\BigDecimal $quantity_one
 * @property \App\Measurements\MeasurementEnum $unit_one
 * @property \Brick\Math\BigDecimal $quantity_two
 * @property \App\Measurements\MeasurementEnum $unit_two
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ingredient $ingredient
 * @method static \Database\Factories\CrossConversionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereQuantityOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereQuantityTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereUnitOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereUnitTwo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrossConversion whereUpdatedAt($value)
 */
	class CrossConversion extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ingredient
 *
 * @property int $id
 * @property string $name
 * @property \Brick\Math\BigDecimal $cleaned_yield
 * @property \Brick\Math\BigDecimal $cooked_yield
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AsPurchased|null $asPurchased
 * @property-read \App\CustomCollections\AsPurchasedCollection|\App\Models\AsPurchased[] $asPurchasedAll
 * @property-read int|null $as_purchased_all_count
 * @property-read \App\CustomCollections\AsPurchasedCollection|\App\Models\AsPurchased[] $asPurchasedHistory
 * @property-read int|null $as_purchased_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CrossConversion[] $crossConversions
 * @property-read int|null $cross_conversions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecipeItem[] $recipeItems
 * @property-read int|null $recipe_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Recipe[] $recipes
 * @property-read int|null $recipes_count
 * @method static \Database\Factories\IngredientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient search($searchString)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCleanedYield($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCookedYield($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereUpdatedAt($value)
 */
	class Ingredient extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingredient[] $ingredients
 * @property-read int|null $ingredients_count
 * @method static \Database\Factories\LocationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MenuCategory
 *
 * @property int $id
 * @property string $name
 * @property \Brick\Math\BigDecimal $costing_goal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Recipe[] $recipes
 * @property-read int|null $recipes_count
 * @method static \Database\Factories\MenuCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereCostingGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereUpdatedAt($value)
 */
	class MenuCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Recipe
 *
 * @property int $id
 * @property string $name
 * @property \Brick\Math\BigDecimal $portions
 * @property \Brick\Money\Money $price
 * @property \Brick\Math\BigDecimal $costing_goal
 * @property int|null $menu_category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ingredient[] $ingredients
 * @property-read int|null $ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecipeItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Models\MenuCategory|null $menuCategory
 * @method static \Database\Factories\RecipeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe search($searchString)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCostingGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereMenuCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe wherePortions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe withAllRelations()
 */
	class Recipe extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RecipeItem
 *
 * @property MeasurementEnum $unit
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property bool $cleaned
 * @property bool $cooked
 * @property \Brick\Math\BigDecimal $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $cost
 * @property-read string $ingredient_name
 * @property-read string $measurement
 * @property-read \App\Models\Ingredient $ingredient
 * @property-read \App\Models\Recipe $recipe
 * @method static \Database\Factories\RecipeItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereCleaned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereCooked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereRecipeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem withFullIngredientRelation()
 */
	class RecipeItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

