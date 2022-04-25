<?php

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
*/

use App\Measurements\MeasurementEnum;
use App\Measurements\MetricVolume;
use App\Measurements\MetricWeight;
use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Support\Str;


/**
 * Brick\Money\Money package is a final class, so since it can't be extended to add more methods, we use a function.
 *
 * @param Money $money
 * @param bool  $withDollarSign
 *
 * @return string
 */
function moneyToString(Money $money, bool $withDollarSign = true, $decimalPlaces = 2): string
{
    $string = $money->to(new CustomContext($decimalPlaces), RoundingMode::UP)->formatTo('en_US');

    if ($withDollarSign) {
        return $string;
    } else {
        return Str::of($string)->after('$');
    }
}


/**
 * Shortcut function for creating a new Money object.
 *
 * @param     $price
 * @param int $decimalPlaces
 *
 * @return Money
 * @throws \Brick\Money\Exception\UnknownCurrencyException
 */
function money($price, int $decimalPlaces = 8): Money
{
    return Money::of($price, 'USD', new CustomContext($decimalPlaces), RoundingMode::HALF_UP);
}

function theme(): string
{
    $availableThemes = collect(config('app.themes'));
    $selected = session('theme', $availableThemes->first());

    $list = $availableThemes->mapWithKeys(function ($item) {
        return [$item => asset("css/$item.css")];
    });

    if ($availableThemes->contains($selected)) {
        return $list[$selected];
    }

    return $list->first();
}

function findMeasurementUnitEnum($value): MeasurementEnum
{
    $classes = [
        UsWeight::class,
        UsVolume::class,
        MetricVolume::class,
        MetricWeight::class
    ];

    foreach ($classes as $class) {
        $possible = $class::fromString($value);
        if ($possible) return $possible;
    }

    throw new Exception("Failed to match '$value' to MeasurementEnum");
}
