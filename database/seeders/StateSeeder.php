<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Gujrat',
                'enable' => true,
            ],
            [
                'name' => 'Karnatak',
                'enable' => true,
            ],
            [
                'name' => 'Maharastra',
                'enable' => true,
            ],
            [
                'name' => 'MP - Madhya Pradesh',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('states')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
