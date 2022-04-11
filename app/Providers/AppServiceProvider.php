<?php

namespace App\Providers;

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Factory::macro('fakePrice', function () {
            return Money::ofMinor(
                $this->faker->numberBetween(100, 2000),
                'USD'
            );
        });

        Factory::macro('fakeUnit', function () {
            return collect(UsWeight::cases())
                ->merge(UsVolume::cases())
                ->random();
        });
    }
}
