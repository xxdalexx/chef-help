<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

/**
 * @property MeasurementEnum $unit
 */
class RecipeItem extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'unit'     => MeasurementEnumCast::class,
        'cleaned'  => 'boolean',
        'cooked'   => 'boolean',
        'quantity' => BigDecimalCast::class
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithFullIngredientRelation($query): Builder
    {
        return $query->with('ingredient.asPurchased');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Accessors
    |--------------------------------------------------------------------------
    */

    public function getIngredientNameAttribute(): string
    {
        //Lazy loading issues with livewire?
        if (! $this->relationLoaded('ingredient') ) {
            $this->load('ingredient.asPurchased');
        }

        return $this->ingredient->name;
    }

    public function getMeasurementAttribute(): string
    {
        return $this->quantity . ' ' . $this->unit->value;
    }

    public function getCostAttribute(): string
    {
        return Cache::remember($this->costCacheKey(), $thirtyDays = 60 * 60 * 24 * 30, function () {
            return $this->getCostAsString();
        });
    }

    public function costCacheKey(): string
    {
        return 'RecipeItemCost' . $this->id . $this->updated_at;
    }

    public function canNotCalculateCostReason(): string
    {
        // Ingredient Missing As Purchased Record
        if (! $this->ingredient->asPurchased) {
            return 'No As Purchased Data';
        }

        // Weight/Volume Check
        if ($this->unit->getType() != $this->ingredient->asPurchased->unit->getType()) {
            return 'No Weight <-> Volume Conversion';
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    protected function crossConversionNeeded(): bool
    {
        return $this->unit->getType() != $this->ingredient->asPurchased->unit->getType();
    }

    public function canCalculateCost(): bool
    {
        // Ingredient Missing As Purchased Record
        if (! $this->ingredient->asPurchased) return false;

        // Weight/Volume Check
        // Yuck FIX/REFACTOR!
        if ( $this->crossConversionNeeded() ) {
            if ( $this->ingredient->crossConversions->first() ) {
                if ( $this->ingredient->crossConversions->first()->canConvertWeightAndVolume() ) {
                    return true;
                } else {return false;}
            } else {return false;}
        }

        return true;
    }

    public function getCost(): Money
    {
        if (! $this->canCalculateCost() ) return money(0);

        // Example to follow
        // Using Flour from RandomRecipeSeeder
        // With a conversion of 128 grams = 1 cup

        // $26/4kg or $6.50/kg or $0.0065/gram
        $costPerApBaseUnit = $this->ingredient->asPurchased->getCostPerBaseUnit();
        $apBaseUnit = $this->ingredient->asPurchased->getBaseUnit();

        // SYSTEMS ARE THE SAME, FIRST NESTED IF STATEMENT SKIPPED/RUNS FALSE
        // Paper Math, with a conversion of 128 grams = 1 cup, this is where we would convert
        // $0.0065/gram -> $0.832/128 gram -> $0.832/1 Cup -> $0.104/floz
        // So we need to manually override and convert the ap from grams to floz before it does the system check.
        // $costPerApBaseUnit = .104
        // $apBaseUnit = UsVolume::floz

        // SYSTEMS ARE DIFFERENT, FIRST NESTED IF RUNS TRUE
        // What's going to happen if we do our conversion with US Weight instead of Metric Weight?
        // Using a conversion of 4.5oz = 1 cup, now the paper conversion is:
        // $0.0065/gram -> $0.184272/oz -> $0.829224/4.5oz -> $0.829224/cup -> $0.104/floz
        // Still need the same override/conversion as before, but now we need to account for the extra step.

        if ( $this->crossConversionNeeded() ) {
            /** @var CrossConversion $conversion */
            $conversion        = $this->ingredient->crossConversions->first();
            $convertingToUnitType = $this->unit->getType();

            // Are the systems different between CrossConversion unit and the AP unit
            if ($apBaseUnit->getSystem() != $conversion->baseUnitOf( $apBaseUnit->getType() )->getSystem()) {
                // Using above example (second paragraph), we're at $0.0065/gram and need to change it to $0.184272/oz
                $systemConversion = match ($apBaseUnit) {
                    MetricVolume::liter => '33.81413',  // 1 Liter = 33.81413 floz
                    UsVolume::floz => '0.02957344',     // 1 floz = 0.02957344 L
                    MetricWeight::gram => '0.03527396', // 1 gram = 0.03527396 oz
                    UsWeight::oz => '28.34952',         // 1 oz = 28.34952 gram
                    default => throw new \Exception('Conversion is not US<->Metric')
                };
                $costPerApBaseUnit = $costPerApBaseUnit->dividedBy($systemConversion, RoundingMode::HALF_UP);
            }

            $factor = match ($convertingToUnitType) {
                'volume' => $conversion->weightToVolumeFactor(),
                'weight' => $conversion->volumeToWeightFactor()
            };

            $costPerApBaseUnit = $costPerApBaseUnit->dividedBy($factor, RoundingMode::HALF_UP);
            $apBaseUnit        = $conversion->baseUnitOf($convertingToUnitType);
        }

        //Until a Misc System is created, we can assume only US<->Metric will hit here.
        if ( $this->unit->getSystem() != $apBaseUnit->getSystem() ) {

            $conversion = match ($apBaseUnit) {
                MetricVolume::liter => '33.81413',  // 1 Liter = 33.81413 floz
                UsVolume::floz => '0.02957344',     // 1 floz = 0.02957344 L
                MetricWeight::gram => '0.03527396', // 1 gram = 0.03527396 oz
                UsWeight::oz => '28.34952',         // 1 oz = 28.34952 gram
                default => throw new \Exception('Conversion is not US<->Metric')
            };

            $costPerApBaseUnit = $costPerApBaseUnit->dividedBy($conversion, RoundingMode::HALF_UP);
        }

        $price = $costPerApBaseUnit
            ->multipliedBy($this->unit->conversionFactor(), RoundingMode::HALF_UP)
            ->multipliedBy($this->quantity, RoundingMode::HALF_UP);

        if ($this->cleaned) {
            $price = $price->dividedBy($this->ingredient->cleanedYieldDecimal(), RoundingMode::HALF_UP);
        }

        if ($this->cooked) {
            $price = $price->dividedBy($this->ingredient->cookedYieldDecimal(), RoundingMode::HALF_UP);
        }

        return $price;
    }

    public function getCostAsString(bool $withDollarSign = true): string
    {
        return moneyToString($this->getCost(), $withDollarSign);
    }

}
