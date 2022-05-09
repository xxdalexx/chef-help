<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;

/**
 * @property-read string $value
 */
interface MeasurementEnum
{
    public function conversionFactor(): BigDecimal;

    public static function fromString(string $unit): self|bool;

    public static function getBaseUnit(): self;

    public function getType(): string;

    public function getSystem(): string;
}
