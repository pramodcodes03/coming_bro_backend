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
                'id' => '0hxwdye7eXR5uAetuY7b',
                'name' => 'Edelweiss General Insurance Company',
            ],
            [
                'id' => '0zGnrSD9bF5aQ3RVEtnx',
                'name' => 'Future Generali Car Insurance',
            ],
            [
                'id' => '5j3ur7bjGJYvbxix42Bx',
                'name' => 'Other',
            ],
            [
                'id' => '5o9QCsIWiBWuw48mtaTV',
                'name' => 'Acko General Insurance',
            ],
            [
                'id' => '7vrSvHLppCLxz23oRfoi',
                'name' => 'Icici Lombard General Insurance',
            ],
            [
                'id' => '9Mo7VUyjo1hZcIkSBIMM',
                'name' => 'Magma Car Insurance',
            ],
            [
                'id' => 'Cvg6vG4b6LUYbzBTzA9V',
                'name' => 'Bajaj Allianz General Insurance ',
            ],
            [
                'id' => 'FGSiD8aaLuxrvyQ2isaO',
                'name' => 'United India Insurance',
            ],
            [
                'id' => 'XS4Vz9cr8JVXsWEwIKVu',
                'name' => 'National Car Insurance',
            ],
            [
                'id' => 'YDP5j2z6SgMda1zN7AkZ',
                'name' => 'Tata Aig General Insurance',
            ],
            [
                'id' => 'bYZpyONALNH10p9odoYq',
                'name' => 'Hdfc Ergo Insurance',
            ],
            [
                'id' => 'euM9qp8vLLUp1eZpGMmt',
                'name' => 'Oriental Insurance',
            ],
            [
                'id' => 'gzKbfGVh4XseSWPEbixT',
                'name' => 'Go Digit General Insurance',
            ],
            [
                'id' => 'jgNtBrX4n7CXurciOCIO',
                'name' => 'Kotak Mahindra Car Insurance',
            ],
            [
                'id' => 'nhG6MKt8cqMh4DQz3B7U',
                'name' => 'Liberty General Insurance',
            ],
            [
                'id' => 'qBpClpXugEixGzhHcmLe',
                'name' => 'New India Assurance',
            ],
            [
                'id' => 'qKrSCtXk1vuO3FTcP1wV',
                'name' => 'Royal Sundaram General Insurance',
            ],
            [
                'id' => 'r7HyYEgUrF4pLBPMQRAo',
                'name' => 'Zuno Car Insurance',
            ],
            [
                'id' => 'slJS7pdIugYmdMArEXyl',
                'name' => 'Chola MS General Insurance',
            ],
            [
                'id' => 'tV9dZK9bAXJ8T7sWrZ1Z',
                'name' => 'Sbi General Insurance',
            ],
            [
                'id' => 'vbffy7uLYDuZ5ek214Vy',
                'name' => 'Shriram General Insurance',
            ],
            [
                'id' => 'vhGJjviAZuJunsnKswpd',
                'name' => 'Iffco Tokio General Insarance',
            ]
        ];

        foreach ($records as $record) {
            DB::table('insurance_companies')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
