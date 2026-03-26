<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $maharastra = DB::table('states')->where('name', 'Maharastra')->value('id');
        $karnatak = DB::table('states')->where('name', 'Karnatak')->value('id');

        $records = [
            [
                'name' => 'Ahilyanagar',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Akola',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Sindhudurga',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Nagpur',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Yavatmal',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Beed',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Pune',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Sambhaji nagar',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Parbhani',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Jalana',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Dhule',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Buldhana',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Solapur',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Ratnagiri',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Vardha',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Raigad',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Palghar',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Gadchiroli',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Kolhapur',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Thane',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Bhandara',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Satara',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Dharashiv',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Gondia',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Sangali',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Mumbai',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Nandurbar',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Hingoli',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Jalgaon',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Nashik',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Washim',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Chandrapur',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Nanded',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Latur',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Amravati',
                'state_id' => $maharastra,
                'enable' => true,
            ],
            [
                'name' => 'Belgaon',
                'state_id' => $karnatak,
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('cities')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
