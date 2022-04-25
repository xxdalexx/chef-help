<?php

namespace App\CustomCollections;

use App\Models\AsPurchased;
use Illuminate\Database\Eloquent\Collection;

class AsPurchasedCollection extends Collection
{
    public function loadVariances(): self
    {
        if ($this->count() < 2 ) return $this;

        $lastId = $this->last()->id;

        /** @var AsPurchased $record */
        foreach ($this as $index => $record) {
            if ($record->id == $lastId) break;
            $next = $index + 1;

            $record->previousCostPerBaseUnit = $this[$next]->getCostPerBaseUnit();
        }

        return $this;
    }
}
