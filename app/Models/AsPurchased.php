<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static AsPurchased find(int $int)
 * @method static AsPurchased create(array $attributes)
 * @method static int count()
 */
class AsPurchased extends Model
{
    use HasFactory;

    protected $table = 'as_purchased';

    protected $casts = [
        'unit'  => MeasurementEnumCast::class,
        'price' => MoneyCast::class
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
