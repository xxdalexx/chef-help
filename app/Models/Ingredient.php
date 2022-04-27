<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

class Ingredient extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'cleaned_yield' => BigDecimalCast::class,
        'cooked_yield' => BigDecimalCast::class
    ];

    protected $touches = ['recipeItems'];

    public static function rules($creatingAsPurchased = false): array
    {
        return [
            'nameInput'       => 'required',
            'cleanedInput'    => 'required|numeric|between:1,100',
            'cookedInput'     => 'required|numeric|between:1,100',

            'apQuantityInput' => ['numeric', Rule::requiredIf($creatingAsPurchased)],
            'apUnitInput'     => [Rule::requiredIf($creatingAsPurchased)],
            'apPriceInput'    => ['numeric', Rule::requiredIf($creatingAsPurchased)],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, $searchString)
    {
        return $query->where('name', 'LIKE', "%$searchString%");
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function asPurchased(): HasOne
    {
        return $this->hasOne(AsPurchased::class)->latest();
    }

    public function asPurchasedHistory(): HasMany
    {
        return $this->hasMany(AsPurchased::class)->take(10)->skip(1)->latest();
    }

    public function asPurchasedAll(): HasMany
    {
        return $this->hasMany(AsPurchased::class)->latest();
    }

    public function recipeItems(): HasMany
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function cleanedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of($this->cleaned_yield)->exactlyDividedBy(100);
    }

    public function cookedYieldDecimal(): BigDecimal
    {
        return BigDecimal::of($this->cooked_yield)->exactlyDividedBy(100);
    }

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    */

    public function showLink(): string
    {
        return route('ingredient.show', $this);
    }

    public function inverseLocations(): \Illuminate\Support\Collection
    {
        return Location::whereNotIn('id', $this->locations->pluck('id'))->get();
    }

    public function inverseLocationIds(): array
    {
        return $this->inverseLocations()->pluck('id')->toArray();
    }

}
