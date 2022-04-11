<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static Ingredient create(array $array)
 * @method static int count()
 */
class Ingredient extends Model
{
    use HasFactory;

    public function asPurchased(): HasOne
    {
        return $this->hasOne(AsPurchased::class);
    }

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }
}
