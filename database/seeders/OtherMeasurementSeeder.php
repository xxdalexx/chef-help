<?php

namespace Database\Seeders;

use App\Models\OtherMeasurement;
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
        OtherMeasurement::create([
            'name' => 'each'
        ]);
    }
}
