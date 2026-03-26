<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleModelSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = fn(string $name) => DB::table('vehicle_companies')->where('name', $name)->value('id');

        $records = [
            [
                'name' => 'TVS King Duramax',
                'company_id' => $companyId('TVS Motor Company Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'TVS King Duramax Plus',
                'company_id' => $companyId('TVS Motor Company Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj RE E TEC 9.0',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Honda Amaze',
                'company_id' => $companyId('Honda Motors'),
                'enable' => true,
            ],
            [
                'name' => 'Mahindra Alfa Plus',
                'company_id' => $companyId('Mahindra & Mahindra Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'TVS King Deluxe',
                'company_id' => $companyId('TVS Motor Company Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Ford Fiesta',
                'company_id' => $companyId('Ford India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Atul Rik +',
                'company_id' => $companyId('Atul Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'TVS King Ep 1',
                'company_id' => $companyId('TVS Motor Company Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti Ertiga',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj GoGo P 70',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Hyundai Xcent',
                'company_id' => $companyId('Hyundai Motor India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Ford Figo Aspire',
                'company_id' => $companyId('Ford India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj Maxima C',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'JSA 3 WHEELER',
                'company_id' => $companyId('JSA'),
                'enable' => true,
            ],
            [
                'name' => 'Mahindra Alfa Champ',
                'company_id' => $companyId('Mahindra & Mahindra Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj Maxima X Wide',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'JSA NV',
                'company_id' => $companyId('JSA'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti Wagon R',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Ford Figo',
                'company_id' => $companyId('Ford India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Ape Plus',
                'company_id' => $companyId('Piaggio Vehicles Pvt Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj RE CNG ',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Hyundai I 20',
                'company_id' => $companyId('Hyundai Motor India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'TVS King EV Max',
                'company_id' => $companyId('TVS Motor Company Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti Ciaz',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Ape City',
                'company_id' => $companyId('Piaggio Vehicles Pvt Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti Dzire',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj Maxima C',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Honda City',
                'company_id' => $companyId('Honda Motors'),
                'enable' => true,
            ],
            [
                'name' => 'Hyundai Aura',
                'company_id' => $companyId('Hyundai Motor India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Ape E Xtra Fx',
                'company_id' => $companyId('Piaggio Vehicles Pvt Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Mahindra E Alfa Super',
                'company_id' => $companyId('Mahindra & Mahindra Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Mahindra Alfa Dx',
                'company_id' => $companyId('Mahindra & Mahindra Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Toyota Rumion',
                'company_id' => $companyId('Toyota Motors'),
                'enable' => true,
            ],
            [
                'name' => 'JSA VICTORY PLUS',
                'company_id' => $companyId('JSA'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti Celario',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Ape Metro',
                'company_id' => $companyId('Piaggio Vehicles Pvt Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Bajaj GoGo P 50',
                'company_id' => $companyId('Bajaj Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Toyota Innova',
                'company_id' => $companyId('Toyota Motors'),
                'enable' => true,
            ],
            [
                'name' => 'Toyota Etios',
                'company_id' => $companyId('Toyota Motors'),
                'enable' => true,
            ],
            [
                'name' => 'Toyota Etios Liva',
                'company_id' => $companyId('Toyota Motors'),
                'enable' => true,
            ],
            [
                'name' => 'Atul Rik',
                'company_id' => $companyId('Atul Auto Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Tata Nexon',
                'company_id' => $companyId('Tata Motors Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Mahindra Treo Plus',
                'company_id' => $companyId('Mahindra & Mahindra Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Tata Zest',
                'company_id' => $companyId('Tata Motors Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Piaggio Ape E City Fx',
                'company_id' => $companyId('Piaggio Vehicles Pvt Ltd'),
                'enable' => true,
            ],
            [
                'name' => 'Maruti XL6',
                'company_id' => $companyId('Maruti Suzuki India Ltd'),
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('vehicle_models')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
