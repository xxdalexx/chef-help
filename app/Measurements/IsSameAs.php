<?php

namespace App\Measurements;

trait IsSameAs
{
    public function isSameAs(MeasurementEnum $check): bool
    {
        return $this == $check;
    }
}
