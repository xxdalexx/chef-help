<?php

namespace App\Measurements;

interface MeasurementEnum
{
    public function conversionFactor(): int;

    public static function fromString(string $unit): self;
}
