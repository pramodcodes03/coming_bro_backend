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
                'id' => 'OmAAERf1KqvfDH0rRAPV',
                'name' => 'Marathi',
                'code' => 'mr',
                'image' => '',
                'enable' => true,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'id' => 'PSosPfebXgmhPxTX4Oqn',
                'name' => 'Hindi',
                'code' => 'hi',
                'image' => '',
                'enable' => true,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'id' => 'oKs39NhwMe7YsGRKLcFD',
                'name' => 'English',
                'code' => 'en',
                'image' => '',
                'enable' => true,
                'is_deleted' => false,
                'is_rtl' => false,
            ],
            [
                'id' => 'rG9ufAeccpVYgoZfAoRq',
                'name' => 'Português',
                'code' => 'pt',
                'image' => '',
                'enable' => false,
                'is_deleted' => true,
                'is_rtl' => false,
            ],
            [
                'id' => 'wQgDuFdNOXrLxyjfZxR2',
                'name' => 'Arabic',
                'code' => 'ar',
                'image' => '',
                'enable' => false,
                'is_deleted' => true,
                'is_rtl' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('languages')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
