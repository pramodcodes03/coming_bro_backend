<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleCompanySeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Honda Motors',
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Vehicles Pvt Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Bajaj Auto Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Atul Auto Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Ford India Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Force Motors',
                'enable' => true,
            ],
            [
                'name' => 'Hyundai Motor India Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Mahindra & Mahindra Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Maruti Suzuki India Ltd',
                'enable' => true,
            ],
            [
                'name' => 'JSA',
                'enable' => true,
            ],
            [
                'name' => 'TVS Motor Company Ltd',
                'enable' => true,
            ],
            [
                'name' => 'Toyota Motors',
                'enable' => true,
            ],
            [
                'name' => 'Tata Motors Ltd',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('vehicle_companies')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
