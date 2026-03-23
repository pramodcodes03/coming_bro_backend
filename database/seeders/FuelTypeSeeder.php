<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => 'AEfMC3omauZ8RtzAFRRo',
                'name' => 'CNG',
                'enable' => true,
            ],
            [
                'id' => 'KYUmefrt1EYfGWnFCo7L',
                'name' => 'Diesel',
                'enable' => true,
            ],
            [
                'id' => 'StMUHxyOmoRa8yBXQ09g',
                'name' => 'Petrol + Cng',
                'enable' => true,
            ],
            [
                'id' => 'V2Y3DG1R4U8JzMHUaJ6s',
                'name' => 'Petrol',
                'enable' => true,
            ],
            [
                'id' => 'o26s9SqrxwwXbztWEe34',
                'name' => 'Lpg',
                'enable' => true,
            ],
            [
                'id' => 'qpbX1QA875b66DKsh9OY',
                'name' => 'Electric',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('fuel_types')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
