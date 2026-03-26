<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/images%2FYellow%20Luxury%20Car%20Showroom%20Facebook%20Cover%20(1920%20x%201080%20px)%20(1)_1737641435795.png?alt=media&token=bcc2b6b7-c2ca-4223-b40b-a049265146d6',
                'position' => '3',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/images%2FYellow%20Luxury%20Car%20Showroom%20Facebook%20Cover%20(1920%20x%201080%20px)_1737641416048.png?alt=media&token=887604b7-4213-468c-96e8-462ad7e19405',
                'position' => '1',
                'enable' => true,
                'is_deleted' => false,
            ],
            [
                'image' => 'https://firebasestorage.googleapis.com/v0/b/goride-1a752.appspot.com/o/images%2Fistockphoto-1307981108-1024x1024_1701928300653.jpg?alt=media&token=0b077abb-f535-448b-8b15-9f10f92f1f25',
                'position' => '4',
                'enable' => false,
                'is_deleted' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('banners')->updateOrInsert(['image' => $record['image']], $record);
        }
    }
}
