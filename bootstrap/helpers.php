<?php

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
*/

use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

function moneyToString(Money $money): string
{
    return $money->to(new CustomContext(2), RoundingMode::UP)->formatTo('en_US');
}

function money($price, $decimalPlaces = 4): Money
{
    return Money::of($price, 'USD', new CustomContext($decimalPlaces), RoundingMode::UP);
}
