<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceCompanySeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Edelweiss General Insurance Company',
            ],
            [
                'name' => 'Future Generali Car Insurance',
            ],
            [
                'name' => 'Other',
            ],
            [
                'name' => 'Acko General Insurance',
            ],
            [
                'name' => 'Icici Lombard General Insurance',
            ],
            [
                'name' => 'Magma Car Insurance',
            ],
            [
                'name' => 'Bajaj Allianz General Insurance ',
            ],
            [
                'name' => 'United India Insurance',
            ],
            [
                'name' => 'National Car Insurance',
            ],
            [
                'name' => 'Tata Aig General Insurance',
            ],
            [
                'name' => 'Hdfc Ergo Insurance',
            ],
            [
                'name' => 'Oriental Insurance',
            ],
            [
                'name' => 'Go Digit General Insurance',
            ],
            [
                'name' => 'Kotak Mahindra Car Insurance',
            ],
            [
                'name' => 'Liberty General Insurance',
            ],
            [
                'name' => 'New India Assurance',
            ],
            [
                'name' => 'Royal Sundaram General Insurance',
            ],
            [
                'name' => 'Zuno Car Insurance',
            ],
            [
                'name' => 'Chola MS General Insurance',
            ],
            [
                'name' => 'Sbi General Insurance',
            ],
            [
                'name' => 'Shriram General Insurance',
            ],
            [
                'name' => 'Iffco Tokio General Insarance',
            ]
        ];

        foreach ($records as $record) {
            DB::table('insurance_companies')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
