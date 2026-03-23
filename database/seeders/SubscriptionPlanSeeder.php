<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => 'Ltdf0HVwFIa6BYBTM6BN',
                'name' => '30 Rs ',
                'amount' => '30',
                'duration' => 'unlimited',
                'description' => 'Unlimited Days 10 Ride',
                'enable' => true,
                'image' => '',
                'gst' => '18',
                'tds' => '0',
                'ride' => '10',
            ]
        ];

        foreach ($records as $record) {
            DB::table('subscription_plans')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
