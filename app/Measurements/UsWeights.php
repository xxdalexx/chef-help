<?php

namespace App\Measurements;

enum UsWeights implements MeasurementEnum
{
    case oz;
    case lb;
    case ton;

    public function conversionFactor(): int
    {
        return match ($this) {
            self::oz => 1,
            self::lb => 16,
            self::ton => 2000
        };
    }

    public static function from(string $unit): self
    {
        $unit = strtolower($unit);

        return match ($unit) {
            'oz', 'ounce' => self::oz,
            'lb', 'pound' => self::lb,
            'ton', => self::ton
        };
    }
}
