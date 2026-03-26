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
                'name' => 'CNG',
                'enable' => true,
            ],
            [
                'name' => 'Diesel',
                'enable' => true,
            ],
            [
                'name' => 'Petrol + Cng',
                'enable' => true,
            ],
            [
                'name' => 'Petrol',
                'enable' => true,
            ],
            [
                'name' => 'Lpg',
                'enable' => true,
            ],
            [
                'name' => 'Electric',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('fuel_types')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
