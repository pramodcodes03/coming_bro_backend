<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'title' => 'Batch Number',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'Cancel Cheque or Passbook ',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'Selfi',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'School leaving certificate',
                'front_side' => false,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'title' => 'Vehicle Insurance',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'title' => 'Police Verification Certificate',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'title' => 'Driving licence',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'title' => 'PAN card',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'delete document test',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'title' => 'Selfi With Car ',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'PUC',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'title' => 'Professional Tax Certificate',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'title' => 'Certificate of registration',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => true,
            ],
            [
                'title' => 'Voter Id',
                'front_side' => true,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'Passport',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'title' => 'Aadhar Card',
                'front_side' => true,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'title' => 'Vehicle R C ',
                'front_side' => true,
                'back_side' => false,
                'enable' => true,
                'expire_at' => false,
                'is_deleted' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('documents')->updateOrInsert(['title' => $record['title']], $record);
        }
    }
}
