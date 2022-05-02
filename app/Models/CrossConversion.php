<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrossConversion extends Model
{
    use HasFactory;

    protected $casts = [
        'unit_one' => MeasurementEnumCast::class,
        'unit_two' => MeasurementEnumCast::class,
        'quantity_one' => BigDecimalCast::class,
        'quantity_two' => BigDecimalCast::class
    ];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
