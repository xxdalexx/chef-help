<?php

namespace App\Measurements;

use Brick\Math\BigDecimal;

enum UsWeight: string implements MeasurementEnum
{
    case oz = 'oz';
    case lb = 'lb';
    case ton = 'ton';

    public function conversionFactor(): BigDecimal
    {
        $number = match ($this) {
            self::oz => 1,
            self::lb => 16,
            self::ton => 2000
        };

        return BigDecimal::of($number);
    }

    public static function fromString(string $unit): self|bool
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'oz', 'ounce' => self::oz,
            'lb', 'pound' => self::lb,
            'ton', => self::ton,
            default => false
        };
    }

    public function getBaseUnit(): MeasurementEnum
    {
        return UsWeight::oz;
    }

    public function getType(): string
    {
        return 'weight';
    }

    public function getSystem(): string
    {
        return 'us';
    }
}
