<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'symbol' => '$',
                'code' => 'USD',
                'name' => 'Us Dollar',
                'enable' => false,
                'symbol_at_right' => false,
                'decimal_digits' => 2,
            ],
            [
                'symbol' => 'INR',
                'code' => 'INR',
                'name' => 'India',
                'enable' => true,
                'symbol_at_right' => true,
                'decimal_digits' => 0,
            ]
        ];

        foreach ($records as $record) {
            DB::table('currencies')->updateOrInsert(['code' => $record['code']], $record);
        }
    }
}
