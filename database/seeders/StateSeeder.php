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
                'id' => 'N86fGl4znS1UOz7IYXpx',
                'name' => 'Gujrat',
                'enable' => true,
            ],
            [
                'id' => 'QeYIZgldsJqLgu1hHs0K',
                'name' => 'Karnatak',
                'enable' => true,
            ],
            [
                'id' => 'euTqaIgQL4Nn8sR8iUFr',
                'name' => 'Maharastra',
                'enable' => true,
            ],
            [
                'id' => 'pIG9yNHm7Ri6c1JQ3VqI',
                'name' => 'MP - Madhya Pradesh',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('states')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
