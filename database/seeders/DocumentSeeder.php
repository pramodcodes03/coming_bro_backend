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
                'id' => 'AKqkJIT74LsyyYfcGI3J',
                'title' => 'Batch Number',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'App4TnJrbWQDHTdhBycJ',
                'title' => 'Cancel Cheque or Passbook ',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'DA9lxMkmul0acytYPFsm',
                'title' => 'Selfi',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'G5F3VLKE3GYvYRfejFXE',
                'title' => 'School leaving certificate',
                'front_side' => false,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'id' => 'JEpRsLcZOypLTUClBtnS',
                'title' => 'Vehicle Insurance',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'id' => 'QSB0tHD8hti8fNaN3t38',
                'title' => 'Police Verification Certificate',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'id' => 'RrCGElEjDT7BNAskdWgG',
                'title' => 'Driving licence',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'id' => 'Ux481xVg623eimeYdv6A',
                'title' => 'PAN card',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'X2x4YX4ljMqIUA1gs7WX',
                'title' => 'delete document test',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'id' => 'eAACY0GPKcFYVQ6Xq9Lu',
                'title' => 'Selfi With Car ',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'hT4pGozDodNXIGluRSJi',
                'title' => 'PUC',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'id' => 'jenro2E1GiR9T0Rz7SUw',
                'title' => 'Professional Tax Certificate',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => false,
            ],
            [
                'id' => 'oSGnbhIeXevhh6qcvc6G',
                'title' => 'Certificate of registration',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => true,
                'is_deleted' => true,
            ],
            [
                'id' => 'sIfN3ThmWFCIMDQWqPqr',
                'title' => 'Voter Id',
                'front_side' => true,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'vGG4Ot67pUz8JoE6uNrl',
                'title' => 'Passport',
                'front_side' => true,
                'back_side' => false,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => true,
            ],
            [
                'id' => 'xXAaDh5hPw6woxTsk4lJ',
                'title' => 'Aadhar Card',
                'front_side' => true,
                'back_side' => true,
                'enable' => false,
                'expire_at' => false,
                'is_deleted' => false,
            ],
            [
                'id' => 'xtKr0woP34g3Gu80JXxa',
                'title' => 'Vehicle R C ',
                'front_side' => true,
                'back_side' => false,
                'enable' => true,
                'expire_at' => false,
                'is_deleted' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('documents')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
