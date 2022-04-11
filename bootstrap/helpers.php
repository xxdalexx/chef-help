<?php

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
*/

use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Support\Str;

function moneyToString(Money $money, $withDollarSign = true): string
{
    $string = $money->to(new CustomContext(2), RoundingMode::UP)->formatTo('en_US');

    if ($withDollarSign) {
        return $string;
    } else {
        return Str::of($string)->after('$');
    }
}

function money($price, $decimalPlaces = 4): Money
{
    return Money::of($price, 'USD', new CustomContext($decimalPlaces), RoundingMode::UP);
}
