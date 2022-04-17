<?php

namespace App\Models;

use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ingredient extends BaseModel
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, $searchString)
    {
        return $query->where('name', 'LIKE', "%$searchString%");
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function asPurchased(): HasOne
    {
        return $this->hasOne(AsPurchased::class);
    }

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function cleanedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of($this->cleaned_yield)->exactlyDividedBy(100);
    }

    public function cookedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of($this->cooked_yield)->exactlyDividedBy(100);
    }

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    */

    public function showLink(): string
    {
        return route('ingredient.show', $this);
    }

}
