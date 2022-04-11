<?php

namespace App\Models;

use App\Casts\MeasurementEnumCast;
use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsPurchased extends BaseModel
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
