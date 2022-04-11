<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static RecipeItem create(array $attributes)
 * @method static int count()
 */
class RecipeItem extends Model
{
    use HasFactory;

    protected $casts = [
        'unit' => MeasurementEnumCast::class
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
