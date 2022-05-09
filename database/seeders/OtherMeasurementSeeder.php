<?php

namespace Database\Seeders;

use App\Models\EachMeasurement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EachMeasurement::create([
            'name' => 'each'
        ]);

        EachMeasurement::create([
            'name' => 'portion'
        ]);
    }
}
