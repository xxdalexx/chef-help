<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Recipe create(array $attributes)
 */
class Recipe extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }
}
