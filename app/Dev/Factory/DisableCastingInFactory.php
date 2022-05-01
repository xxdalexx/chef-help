<?php

namespace App\Dev\Factory;

use Illuminate\Database\Eloquent\Model;

trait DisableCastingInFactory
{
    public function make($attributes = [], ?Model $parent = null)
    {
        $this->modelName()::disableCasts();

        $return = parent::make($attributes, $parent);

        $this->modelName()::reEnableCasts();

        return $return;
    }
}
