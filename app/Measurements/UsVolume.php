<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;
use Brick\Math\BigRational;
use Brick\Math\RoundingMode;

enum UsVolume: string implements MeasurementEnum
{
    use IsSameAs;

    case tsp = 'tsp';
    case tbsp = 'tbsp';
    case floz = 'floz';
    case cup = 'cup';
    case pint = 'pint';
    case quart = 'quart';
    case gallon = 'gallon';

    public function conversionFactor(): BigDecimal
    {
        $number = match ($this) {
            self::tsp => '1/6',
            self::tbsp => .5,
            self::floz => 1,
            self::cup => 8,
            self::pint => 16,
            self::quart => 32,
            self::gallon => 128,
        };

        if (is_string($number)) {
            return BigDecimal::of(1)->dividedBy(6, 10, RoundingMode::UP);
        }

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
            'tbsp', 'table spoon' => self::tbsp,
            'tsp', 'tea spoon' => self::tsp,
            default => false
        };
    }

    public function getBaseUnit(): MeasurementEnum
    {
        return UsVolume::floz;
    }

    public function getType(): string
    {
        return 'volume';
    }

    public function getSystem(): string
    {
        return 'us';
    }
}
