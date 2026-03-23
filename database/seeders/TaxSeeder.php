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
                'id' => '6479c7d3db496',
                'title' => 'IGST',
                'type' => 'percentage',
                'tax' => '9',
                'country' => 'India',
                'enable' => false,
            ],
            [
                'id' => '72w6OSocHrbOzxAxFlbL',
                'title' => 'CGST',
                'type' => 'percentage',
                'tax' => '9',
                'country' => 'India',
                'enable' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('taxes')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
