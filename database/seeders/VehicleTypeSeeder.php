<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $serviceId = fn(string $title) => DB::table('services')->where('title', $title)->value('id');

        $records = [
            [
                'name' => 'Freight',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => null,
            ],
            [
                'name' => 'Cab ',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => $serviceId('Cab '),
            ],
            [
                'name' => 'Innova',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => null,
            ],
            [
                'name' => 'Auto',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => $serviceId('Auto'),
            ],
            [
                'name' => 'Sedan ',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => $serviceId(' Sedan'),
            ],
            [
                'name' => 'Suv',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => $serviceId('Suv'),
            ],
            [
                'name' => 'test',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => $serviceId('test'),
            ]
        ];

        foreach ($records as $record) {
            DB::table('vehicle_types')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
