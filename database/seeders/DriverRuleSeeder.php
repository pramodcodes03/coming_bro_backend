<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverRuleSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Max, 2 in the back',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fmaximum-of-two-people-at-a-time---prohibited-600x600_1691734319449.jpg?alt=media&token=d37c574d-88c1-428f-bcc5-0b7f3786c8fd',
                'enable' => false,
                'is_deleted' => false,
            ],
            [
                'name' => 'Eating is not allowed in the car',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Feating%20not%20allowed_1691733576802.jpg?alt=media&token=bf3e0ded-eca2-420d-9762-522ee42e2c19',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Sorry, not a pet',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fno-pets-allowed-sign-clipart-1_1691733805076.png?alt=media&token=be1a52fd-492f-42b4-90c6-96ac75cb0cf0',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Max',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fcosmetic-cream-product-ads-against-600w-1837685677_1687870197017.webp?alt=media&token=7b2f375b-0b5d-42b5-98b0-e8b69736a73c',
                'enable' => false,
                'is_deleted' => false,
            ],
            [
                'name' => '$5 additional person fee',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fbox_1697481161091.png?alt=media&token=306146ce-3a5c-4e07-a2d8-46ed1ee61f5c',
                'enable' => false,
                'is_deleted' => true,
            ],
            [
                'name' => 'Test Max',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Frealistic-circular-diagram-infographic_23-2148980965_1707258948886.jpg?alt=media&token=16cfec59-b0f3-4d2f-9818-2138cb1d13a5',
                'enable' => false,
                'is_deleted' => false,
            ],
            [
                'name' => 'Please, no smoking in the car',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fno%20smoking_1691733738568.jpg?alt=media&token=976d152e-8d1f-4874-ab65-0903b47c70a5',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'No Alcohol closed/open',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Falvey_1691792899903.png?alt=media&token=fb3f2e39-e0f4-4b6b-8e2e-e972b1d72798',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Laguage 15 kg Allowed ',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2FIMG_20230222_105254_1692149995314.jpg?alt=media&token=230dde22-769b-44a7-b52b-712c87e6876e',
                'enable' => false,
                'is_deleted' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('driver_rules')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
