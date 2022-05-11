<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;

enum MetricVolume: string implements MeasurementEnum
{
    case liter = 'liter';
    case ml = 'ml';

    public function conversionFactor(): BigDecimal
    {
        $number = match ($this) {
            self::liter => 1,
            self::ml => '0.001',
        };
        return BigDecimal::of($number);
    }

    public static function fromString(string $unit): self|bool
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'l', 'liter' => self::liter,
            'ml', 'milliliter' => self::ml,
            default => false
        };
    }

    public function getBaseUnit(): MeasurementEnum
    {
        return self::liter;
    }

    public function getType(): string
    {
        return 'volume';
    }

    public function getSystem(): string
    {
        return 'metric';
    }
}
