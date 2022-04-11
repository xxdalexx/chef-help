<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;

enum MetricWeight: string implements MeasurementEnum
{
    case gram = 'gram';
    case kg = 'kg';

    public function conversionFactor(): BigDecimal
    {
        $number = match ($this) {
            self::gram => 1,
            self::kg => 1000,
        };

        return BigDecimal::of($number);
    }

    public static function fromString(string $unit): self|bool
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'g', 'gram' => self::gram,
            'kg', 'kilogram' => self::kg,
            default => false
        };
    }

    public static function getBaseUnit(): MeasurementEnum
    {
        return self::gram;
    }
}
