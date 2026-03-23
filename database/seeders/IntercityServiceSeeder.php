<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntercityServiceSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => '647f340e35553',
                'name' => 'Tempo Traveler',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/intercityService%2FIMG-20250117-WA0015_1737458415184.jpg?alt=media&token=31263474-3cc0-4516-bcf4-447688f4f665',
                'km_charge' => '18',
                'basic_fare_km' => '0',
                'basic_fare_charges' => '0',
                'holding_charge_minute' => '0',
                'holding_charges' => '0',
                'ride_time_fare_per_minute' => '0',
                'ac_charges' => '',
                'is_ac' => false,
                'enable' => false,
                'offer_rate' => false,
                'admin_commission' => '{"isEnabled": false, "amount": "0", "type": "fix"}',
            ],
            [
                'id' => '647f350983ba2',
                'name' => 'Parcel - Two Wheeler',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/intercityService%2FParcel_1737474923496.png?alt=media&token=f4a2bfca-0881-4506-9428-5da4bd12fb0b',
                'km_charge' => '60',
                'basic_fare_km' => '0',
                'basic_fare_charges' => '0',
                'holding_charge_minute' => '0',
                'holding_charges' => '0',
                'ride_time_fare_per_minute' => '0',
                'ac_charges' => '',
                'is_ac' => false,
                'enable' => false,
                'offer_rate' => false,
                'admin_commission' => '{"isEnabled": false, "type": "fix", "amount": "0"}',
            ],
            [
                'id' => 'Kn2VEnPI3ikF58uK8YqY',
                'name' => 'Freight',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/intercityService%2FFrieght%20Vehicle_1737475635501.png?alt=media&token=73580082-1566-4326-a74e-b5dd76c3ad15',
                'km_charge' => '15',
                'basic_fare_km' => '0',
                'basic_fare_charges' => '0',
                'holding_charge_minute' => '0',
                'holding_charges' => '0',
                'ride_time_fare_per_minute' => '0',
                'ac_charges' => '',
                'is_ac' => false,
                'enable' => false,
                'offer_rate' => false,
                'admin_commission' => '{"isEnabled": false, "amount": "0", "type": "fix"}',
            ],
            [
                'id' => 'UmQ2bjWTnlwoKqdCIlTr',
                'name' => 'Intercity',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/intercityService%2FErtiga_1742310859216.jpg?alt=media&token=41527efc-a0a7-4a7e-a188-542abe17f604',
                'km_charge' => '15',
                'basic_fare_km' => '0',
                'basic_fare_charges' => '0',
                'holding_charge_minute' => '0',
                'holding_charges' => '0',
                'ride_time_fare_per_minute' => '0',
                'ac_charges' => '18',
                'is_ac' => true,
                'enable' => false,
                'offer_rate' => false,
                'admin_commission' => '{"amount": "0", "isEnabled": false, "type": "fix"}',
            ]
        ];

        foreach ($records as $record) {
            DB::table('intercity_services')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
