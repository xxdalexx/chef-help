<?php

namespace App\Models;

use App\Measurements\MeasurementEnum;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EachMeasurement extends BaseModel implements MeasurementEnum
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | MeasurementEnum Methods
    |--------------------------------------------------------------------------
    */

    public static function fromString(string $unit): self|bool
    {
        return self::where('name', $unit)->first() ?? false;
    }

    public function getBaseUnit(): MeasurementEnum
    {
        return $this;
    }

    public function conversionFactor(): BigDecimal
    {
        return BigDecimal::of(1);
    }

    public function getType(): string
    {
        return 'each';
    }

    public function getSystem(): string
    {
        return 'other';
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getValueAttribute(): string
    {
        if (! isset($this->name)) dd($this);
        return $this->attributes['name'];
    }

}
