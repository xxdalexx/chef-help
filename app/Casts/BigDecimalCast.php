<?php

namespace App\Casts;

use Brick\Math\BigDecimal;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BigDecimalCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return BigDecimal
     */
    public function get($model, string $key, $value, array $attributes): BigDecimal
    {
        $value ??= '0';
        return BigDecimal::of($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return (string) $value;
    }
}
