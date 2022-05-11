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

    public function getBaseUnit(): self;

    public function getType(): string;

    public function getSystem(): string;

    public function isSameAs(MeasurementEnum $check): bool;
}
