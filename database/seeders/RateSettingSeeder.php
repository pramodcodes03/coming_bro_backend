<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSettingSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'data' => '{"ratePerKm": "10", "userId": "OOcpcM7NgMRz0Pt1udgZ8OoLRus1"}',
            ],
            [
                'data' => '{"ratePerKm": "10", "userId": "W3Z56xJk4vYTtvN1MHpVnC5dKWg2"}',
            ],
            [
                'data' => '{"ratePerKm": "6", "userId": "begqcYhRjhgd4aGy2YC7rxLUe5P2"}',
            ],
            [
                'data' => '{"ratePerKm": "14", "userId": "v7kVYxjII5aXlviZjBT2qJI7JzI2"}',
            ],
            [
                'data' => '{"ratePerKm": "8", "userId": "zMpiXQvrTrWRPLYzpPDjbFN7lNo1"}',
            ]
        ];

        foreach ($records as $record) {
            DB::table('rate_settings')->updateOrInsert(['data' => $record['data']], $record);
        }
    }
}
