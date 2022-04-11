<?php

namespace Database\Seeders;

use App\Measurements\UsVolume;
use App\Measurements\UsWeight;
use App\Models\AsPurchased;
use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsPurchasedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AsPurchased::create([
            'ingredient_id' => Ingredient::whereName('Lobster')->first()->id,
            'quantity' => '1',
            'unit' => UsWeight::lb,
            'price' => '12.00',
        ]);

        AsPurchased::create([
            'ingredient_id' => Ingredient::whereName('Heavy Cream')->first()->id,
            'quantity' => '1',
            'unit' => UsVolume::quart,
            'price' => '6.42',
        ]);
    }
}
