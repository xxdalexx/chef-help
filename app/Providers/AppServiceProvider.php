<?php

namespace App\Providers;

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
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
            $first = (string) $this->faker->randomDigit();
            $second = (string) $this->faker->numberBetween(10,99);
            return $first .".". $second;
        });

        Factory::macro('fakeUnit', function () {
            return collect(UsWeight::cases())
                ->merge(UsVolume::cases())
                ->pluck('value')
                ->random();
        });
    }
}
