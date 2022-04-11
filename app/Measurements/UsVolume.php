<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;

enum UsVolume: string implements MeasurementEnum
{
    case floz = 'floz';
    case cup = 'cup';
    case pint = 'pint';
    case quart = 'quart';
    case gallon = 'gallon';

    public function conversionFactor(): BigDecimal
    {
        $number = match ($this) {
            self::floz => 1,
            self::cup => 8,
            self::pint => 16,
            self::quart => 32,
            self::gallon => 128
        };

        return BigDecimal::of($number);
    }

    public static function fromString(string $unit): MeasurementEnum|bool
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'floz', 'fl oz', 'fluid oz', 'fluid ounce' => self::floz,
            'cup' => self::cup,
            'pint', 'pt' => self::pint,
            'quart', 'qt' => self::quart,
            'gal', 'gallon' => self::gallon,
            default => false
        };
    }

    public static function getBaseUnit(): MeasurementEnum
    {
        return UsVolume::floz;
    }
}
