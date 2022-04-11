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
 * @property int $quantity
 * @property mixed $unit
 * @property mixed $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ingredient $ingredient
 * @method static \Database\Factories\AsPurchasedFactory factory(...$parameters)
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
 * App\Models\Ingredient
 *
 * @property int $id
 * @property string $name
 * @property int $cleaned_yield
 * @property int $cooked_yield
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AsPurchased|null $asPurchased
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecipeItem[] $recipeItems
 * @property-read int|null $recipe_items_count
 * @method static \Database\Factories\IngredientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient query()
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
 * App\Models\Recipe
 *
 * @property int $id
 * @property string $name
 * @property int $portions
 * @property mixed $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RecipeItem[] $items
 * @property-read int|null $items_count
 * @method static \Database\Factories\RecipeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe query()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereId($value)
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
 * @property int $id
 * @property int $recipe_id
 * @property int $ingredient_id
 * @property mixed $unit
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ingredient $ingredient
 * @property-read \App\Models\Recipe $recipe
 * @method static \Database\Factories\RecipeItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecipeItem query()
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

