<?php

namespace App\Casts;

use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MeasurementEnumCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $possible = UsWeight::fromString($value);
        if ($possible) return $possible;

        $possible = UsVolume::fromString($value);
        if ($possible) return $possible;

        $possible = MetricWeight::fromString($value);
        if ($possible) return $possible;

        $possible = MetricVolume::fromString($value);
        if ($possible) return $possible;

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value->value;
    }
}
