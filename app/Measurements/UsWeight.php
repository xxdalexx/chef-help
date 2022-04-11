<?php

namespace App\Measurements;

enum UsWeight: string implements MeasurementEnum
{
    case oz = 'oz';
    case lb = 'lb';
    case ton = 'ton';

    public function conversionFactor(): int
    {
        return match ($this) {
            self::oz => 1,
            self::lb => 16,
            self::ton => 2000
        };
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
}
