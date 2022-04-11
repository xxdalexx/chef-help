<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Recipe create(array $attributes)
 */
class Recipe extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => MoneyCast::class
    ];
}
