<?php

namespace App\Measurements;

enum UsVolume implements MeasurementEnum
{
    case floz;
    case cup;
    case pint;
    case quart;
    case gallon;

    public function conversionFactor(): int
    {
        return match ($this) {
            self::floz => 1,
            self::cup => 8,
            self::pint => 16,
            self::quart => 32,
            self::gallon => 128
        };
    }

    public static function from(string $unit): MeasurementEnum
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'floz', 'fl oz', 'fluid oz', 'fluid ounce' => self::floz,
            'cup' => self::cup,
            'pint', 'pt' => self::pint,
            'quart', 'qt' => self::quart,
            'gal', 'gallon' => self::gallon
        };
    }
}
