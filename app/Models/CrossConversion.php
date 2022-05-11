<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function canConvertTypes(): bool
    {
        // Check for if the user put in two volumes or two weights.
        return $this->unit_one->getType() != $this->unit_two->getType();
    }

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

}
