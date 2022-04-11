<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::create([
            'name' => 'Lobster',
            'cleaned_yield' => 80,
            'cooked_yield' => 80
        ]);

        Ingredient::create([
            'name' => 'Heavy Cream',
            'cleaned_yield' => 100,
            'cooked_yield' => 100
        ]);
    }
}
