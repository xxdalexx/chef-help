<?php

namespace App\Models;

use App\Casts\BigDecimalCast;
use App\Casts\MeasurementEnumCast;
use App\Casts\MoneyCast;
use App\CustomCollections\AsPurchasedCollection;
use App\Measurements\MeasurementEnum;
use App\ValueObjects\ConvertableUnit;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AsPurchased extends BaseModel
{
    use HasFactory;

    protected $table = 'as_purchased';

    protected ConvertableUnit $convertableUnit;

    protected $casts = [
        'unit'  => MeasurementEnumCast::class,
        'price' => MoneyCast::class,
        'quantity' => BigDecimalCast::class
    ];

    protected $touches = ['ingredient'];

    /**
     * Dynamically populated by loadVariances() on AsPurchasedCollection
     */
    public Money|bool $previousCostPerBaseUnit = false;

    public function newCollection(array $models = []): AsPurchasedCollection
    {
        return new AsPurchasedCollection($models);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getPriceAsString(): string
    {
        return moneyToString($this->price);
    }

    public function getConvertableUnit(): ConvertableUnit
    {
        if (empty ($this->convertableUnit)) {
            return new ConvertableUnit($this->unit, $this->quantity);
        }
        return $this->convertableUnit;
    }

    public function getCostPerBaseUnit(): Money
    {
        return $this->price
            ->dividedBy($this->unit->conversionFactor(), RoundingMode::HALF_UP)
            ->dividedBy($this->quantity, RoundingMode::HALF_UP);
    }

    public function getBaseUnit(): MeasurementEnum
    {
        return $this->unit::getBaseUnit();
    }

    public function getVariancePercentage()
    {
        if (! $this->previousCostPerBaseUnit) return BigDecimal::of(0);
        $previous = $this->previousCostPerBaseUnit->getAmount();
        $current = $this->getCostPerBaseUnit()->getAmount();
        return $current->dividedBy($previous, 4, RoundingMode::HALF_UP);
    }

    public function getVariancePercentageAsString(): string
    {
        return (string) (float) (string) $this->getVariancePercentage()->multipliedBy(100)->minus(100);
    }

}
