<?php

namespace Database\Seeders;

use App\Models\EachMeasurement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EachMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'each',
            'box',
            'bunch',
            'bundle',
            'case',
            'head',
            'pack',
            'portion',
            'sprig',
        ];

        foreach ($items as $name) {
            EachMeasurement::create([
                'name' => $name
            ]);
        }
    }
}
