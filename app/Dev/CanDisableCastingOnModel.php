<?php

namespace App\Dev;

use Illuminate\Database\Eloquent\Builder;

trait CanDisableCastingOnModel
{
    /**
     * Indicates if casting is enabled.
     *
     * @var bool
     */
    protected static $shouldCast = true;

    /**
     * Disable attribute casting on model.
     *
     * @param  bool  $state
     * @return void
     */
    public static function disableCasts($state = true)
    {
        static::$shouldCast = false;
    }

    /**
     * Enable casting on model.
     *
     * @return void
     */
    public static function reEnableCasts()
    {
        static::$shouldCast = true;
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        if (! static::$shouldCast) {
            return [];
        }

        if ($this->getIncrementing()) {
            return array_merge([$this->getKeyName() => $this->getKeyType()], $this->casts);
        }

        return $this->casts;
    }

    /**
     * Save a new model ignoring casts and return the instance.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public static function createWithoutCasting(array $attributes = [])
    {
        static::disableCasts();
        $return = static::create($attributes);
        static::reEnableCasts();

        return $return;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function updateWithoutCasting(array $attributes = [], array $options = [])
    {
        static::disableCasts();
        if (! $this->exists) {
            return false;
        }

        $return = $this->fill($attributes)->save($options);
        static::reEnableCasts();

        return $return;
    }
}
