<?php

namespace App\Models;

use App\Casts\MoneyCast;
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

}
