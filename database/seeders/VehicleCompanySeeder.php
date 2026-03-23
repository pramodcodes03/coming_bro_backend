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
                'id' => '4ez2wgeqyijTgBaIwYvt',
                'name' => 'Honda Motors',
                'enable' => true,
            ],
            [
                'id' => '4t8Zhmmv2WH5w9jScaEQ',
                'name' => 'Piaggio Vehicles Pvt Ltd',
                'enable' => true,
            ],
            [
                'id' => 'CtVlx1XmvbXYxRurJTri',
                'name' => 'Bajaj Auto Ltd',
                'enable' => true,
            ],
            [
                'id' => 'DicSuP6axRZ4CK89R5dX',
                'name' => 'Atul Auto Ltd',
                'enable' => true,
            ],
            [
                'id' => 'FyFOOjKUePq4JnpoiKQ6',
                'name' => 'Ford India Ltd',
                'enable' => true,
            ],
            [
                'id' => 'HAMwyK4gAvc8MSz0NCFN',
                'name' => 'Force Motors',
                'enable' => true,
            ],
            [
                'id' => 'OckrIb09jENsQwyrfxLu',
                'name' => 'Hyundai Motor India Ltd',
                'enable' => true,
            ],
            [
                'id' => 'PeT3bXyti7z20iKsSeR9',
                'name' => 'Mahindra & Mahindra Ltd',
                'enable' => true,
            ],
            [
                'id' => 'bctz9i661HD8p6g1u1oS',
                'name' => 'Maruti Suzuki India Ltd',
                'enable' => true,
            ],
            [
                'id' => 'dMAJyGTUhbTCXtp2m29B',
                'name' => 'JSA',
                'enable' => true,
            ],
            [
                'id' => 'ttU3fbcellItU5CYsGu3',
                'name' => 'TVS Motor Company Ltd',
                'enable' => true,
            ],
            [
                'id' => 'yS20pN79C51jjRjDwjnL',
                'name' => 'Toyota Motors',
                'enable' => true,
            ],
            [
                'id' => 'zDHZjTos0ceQnIehbXyp',
                'name' => 'Tata Motors Ltd',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('vehicle_companies')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
