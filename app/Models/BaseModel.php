<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BaseModel extends Model
{
    protected $guarded = [];

    public function dumpInfo($dump = true): array
    {
        $details = [];
        $details['Type'] = get_debug_type($this);
        $attributes = Arr::except($this->attributes, ['created_at', 'updated_at']);
        $dumpInfo = array_merge($details, $attributes);

        foreach ($this->getRelations() as $model) {
            $dumpInfo[get_debug_type($model)][] = $model->dumpInfo(false);
        }

        if ($dump) {
            dump($dumpInfo);
        }

        return $dumpInfo;
    }
}
