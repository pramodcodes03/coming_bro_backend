<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'title' => 'IGST',
                'type' => 'percentage',
                'tax' => '9',
                'country' => 'India',
                'enable' => false,
            ],
            [
                'title' => 'CGST',
                'type' => 'percentage',
                'tax' => '9',
                'country' => 'India',
                'enable' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('taxes')->updateOrInsert(['title' => $record['title']], $record);
        }
    }
}
