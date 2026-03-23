<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => '1RvtLcbAPyAyEr3d40LF',
                'name' => 'Freight',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => null,
            ],
            [
                'id' => '2yH3nXHjdumO1yfUfm0d',
                'name' => 'Cab ',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => 'JCH5ciDxfOeuQDX2nbyZ',
            ],
            [
                'id' => '42ph0wkd4b6KoImrj0gj',
                'name' => 'Innova',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => null,
            ],
            [
                'id' => 'NJZPuhfsPMm5dNOMXOnp',
                'name' => 'Auto',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => 'GlW3GhOBkbu2gtJwr6jH',
            ],
            [
                'id' => 'bc8ItwNooNAtcME5CiTo',
                'name' => 'Sedan ',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => 'VXmBuNgKxOomZzc8iOgV',
            ],
            [
                'id' => 'nGi5FvL9dy1uQwQyR5QK',
                'name' => 'Suv',
                'enable' => true,
                'front_image' => '',
                'back_image' => '',
                'service_id' => 'vGIpx48e4L5KgW55M5FW',
            ],
            [
                'id' => 'ufEUv0EL28lINkou3Tq2',
                'name' => 'test',
                'enable' => false,
                'front_image' => '',
                'back_image' => '',
                'service_id' => 'slb7TOd3StLENu9YQJnv',
            ]
        ];

        foreach ($records as $record) {
            DB::table('vehicle_types')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
