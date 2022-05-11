<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class CrossConversion extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit_one' => MeasurementEnumCast::class,
        'unit_two' => MeasurementEnumCast::class,
        'quantity_one' => BigDecimalCast::class,
        'quantity_two' => BigDecimalCast::class
    ];

    protected $touches = ['ingredient'];

    public function ingredient(): BelongsTo
    {
        return $this->morphTo('ingredient');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFirstMeasurementAsString(): string
    {
        return (string) $this->quantity_one . ' ' . $this->unit_one->value;
    }

    public function getSecondMeasurementAsString(): string
    {
        return (string) $this->quantity_two . ' ' . $this->unit_two->value;
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function conversionType(): array
    {
        return [$this->unit_one->getType(), $this->unit_two->getType()];
    }

    public function canConvert(array $check): bool
    {
        return array_values(Arr::sort($this->conversionType())) == array_values(Arr::sort($check));
    }

    /*
    |--------------------------------------------------------------------------
    | Weight Volume Conversions
    |--------------------------------------------------------------------------
    */

    public function getFactorFor(string $type): BigDecimal
    {
        if ($this->unit_one->getType() == $type) {
            return $this->quantity_one->multipliedBy( $this->unit_one->conversionFactor() );
        }
        return $this->quantity_two->multipliedBy( $this->unit_two->conversionFactor() );
    }

    public function weightToVolumeFactor(): BigDecimal
    {
        $weight = $this->getFactorFor('weight');
        $volume = $this->getFactorFor('volume');

        return $volume->dividedBy($weight, 8, RoundingMode::HALF_UP);
    }

    public function volumeToWeightFactor(): BigDecimal
    {
        $weight = $this->getFactorFor('weight');
        $volume = $this->getFactorFor('volume');

        return $weight->dividedBy($volume, 8, RoundingMode::HALF_UP);
    }

    public function baseUnitOf(string $type): MeasurementEnum
    {
        if ($this->unit_one->getType() == $type) {
            return $this->unit_one->getBaseUnit();
        }
        return $this->unit_two->getBaseUnit();
    }

    /*
    |--------------------------------------------------------------------------
    | EachMeasurement to Weight or Volume Conversions
    |--------------------------------------------------------------------------
    */

    public function containsEach(): bool
    {
        return
            $this->unit_one->getType() == 'each' ||
            $this->unit_two->getType() == 'each';
    }

    public function getEachUnit(): MeasurementEnum
    {
        throw_if(! $this->containsEach() , \Exception::class);
        if ($this->unit_one->getType() == 'each') {
            return $this->unit_one;
        }
        return $this->unit_two;
    }

    public function getNotEachUnit(): MeasurementEnum
    {
        throw_if(! $this->containsEach() , \Exception::class);
        if ($this->unit_one->getType() != 'each') {
            return $this->unit_one;
        }
        return $this->unit_two;
    }

    public function getEachQuantity(): BigDecimal
    {
        throw_if(! $this->containsEach() , \Exception::class);
        if ($this->unit_one->getType() == 'each') {
            return $this->quantity_one;
        }
        return $this->quantity_two;
    }

    /*
    |--------------------------------------------------------------------------
    | EachMeasurement to EachMeasurement conversions
    |--------------------------------------------------------------------------
    */

    public function canConvertEachToEach(string $firstUnitName = null, string $secondUnitName = null): bool
    {
        if (is_null($firstUnitName) && is_null($secondUnitName)) {
            return $this->unit_one instanceof EachMeasurement && $this->unit_two instanceof EachMeasurement;
        }

        if ($this->unit_one->name == $firstUnitName && $this->unit_two->name == $secondUnitName) {
            return true;
        }

        if ($this->unit_two->name == $firstUnitName && $this->unit_one->name == $secondUnitName) {
            return true;
        }

        return false;
    }

    public function getEachMeasurementUnitQuantityByName(string $name): BigDecimal
    {
        if ($this->unit_one->name == $name) {
            return $this->quantity_one;
        } elseif ($this->unit_two->name == $name) {
            return $this->quantity_two;
        }
        throw new \Exception('Neither unit matches name.');
    }

}
