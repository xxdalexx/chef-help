<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'costing_goal' => BigDecimalCast::class
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function getCostingGoalAsString(): string
    {
        return (string) $this->costing_goal;
    }
}
