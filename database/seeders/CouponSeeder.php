<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'title' => 'ride offer',
                'code' => 'RIDE10',
                'amount' => '10',
                'type' => 'fix',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => true,
                'validity' => '2024-09-20 00:00:00',
            ],
            [
                'title' => 'UPTO 25 OFF',
                'code' => 'FIRST20',
                'amount' => '25',
                'type' => 'fix',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => true,
                'validity' => '2023-05-31 06:36:07',
            ],
            [
                'title' => 'NewOffer',
                'code' => 'NEWOFF9',
                'amount' => '9',
                'type' => 'percentage',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => false,
                'validity' => '2023-06-30 00:00:00',
            ],
            [
                'title' => 'FIX',
                'code' => 'DQS',
                'amount' => '24',
                'type' => 'fix',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => false,
                'validity' => '2023-12-06 00:00:00',
            ],
            [
                'title' => 'Rickshaw ',
                'code' => 'RIDE',
                'amount' => '3',
                'type' => 'fix',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => true,
                'validity' => '2026-01-01 00:00:00',
            ],
            [
                'title' => 'Upto 10% OFF',
                'code' => 'NEW10',
                'amount' => '10',
                'type' => 'percentage',
                'enable' => false,
                'is_deleted' => false,
                'is_public' => true,
                'validity' => '2023-08-04 00:00:00',
            ]
        ];

        foreach ($records as $record) {
            DB::table('coupons')->updateOrInsert(['title' => $record['title']], $record);
        }
    }
}
