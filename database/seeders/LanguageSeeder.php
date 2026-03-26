<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Marathi',
                'code' => 'mr',
                'image' => '',
                'enable' => true,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'name' => 'Hindi',
                'code' => 'hi',
                'image' => '',
                'enable' => true,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'image' => '',
                'enable' => true,
                'is_deleted' => false,
                'is_rtl' => false,
            ],
            [
                'name' => 'Português',
                'code' => 'pt',
                'image' => '',
                'enable' => false,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'name' => 'Arabic',
                'code' => 'ar',
                'image' => '',
                'enable' => false,
                'is_deleted' => true,
                'is_rtl' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('languages')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
