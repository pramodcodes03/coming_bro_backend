<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'airport_name' => 'Indore Airport',
                'airport_lat' => '22.7287381',
                'airport_lng' => '75.80759929999999',
                'city_location' => 'Indore',
                'state' => 'Madhya Pradesh',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Chhatrapati Shivaji Maharaj International Airport',
                'airport_lat' => '19.0902179',
                'airport_lng' => '72.8628087',
                'city_location' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Dabolim Airport (GOI), Dabolim, Goa, India',
                'airport_lat' => '15.3803485',
                'airport_lng' => '73.8349952',
                'city_location' => 'Dabolim',
                'state' => 'Goa',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Dr. Babasaheb Ambedkar International Airport',
                'airport_lat' => '21.0901569',
                'airport_lng' => '79.0548086',
                'city_location' => 'Nagpur',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Chhatrapati Shivaji Maharaj International Airport, Mumbai',
                'airport_lat' => '19.0902179',
                'airport_lng' => '72.8628087',
                'city_location' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Rajkot International Airport',
                'airport_lat' => '22.3799807',
                'airport_lng' => '71.0329185',
                'city_location' => 'Hirasar',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Surat',
                'airport_lat' => '21.1702401',
                'airport_lng' => '72.83106070000001',
                'city_location' => 'Surat',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Jayprakash Narayan International Airport, Patna',
                'airport_lat' => '25.5954395',
                'airport_lng' => '85.0920805',
                'city_location' => 'Patna',
                'state' => 'Bihar',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Sardar Vallabhbhai Patel International Airport',
                'airport_lat' => '23.0744987',
                'airport_lng' => '72.62403549999999',
                'city_location' => 'Ahmedabad',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Air Force Station Bhuj',
                'airport_lat' => '23.2774959',
                'airport_lng' => '69.6781069',
                'city_location' => 'Bhuj',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Pune International Airport',
                'airport_lat' => '18.579343',
                'airport_lng' => '73.9089168',
                'city_location' => 'Pune',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Aurangabad Airport',
                'airport_lat' => '19.8639942',
                'airport_lng' => '75.39606730000001',
                'city_location' => 'Chhatrapati Sambhaji Nagar',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Ahmedabad International Airport',
                'airport_lat' => '23.0718055',
                'airport_lng' => '72.6197238',
                'city_location' => 'Ahmedabad',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Mumbai Airport T2 International',
                'airport_lat' => '19.1010177',
                'airport_lng' => '72.8744502',
                'city_location' => 'Mumbai',
                'state' => 'Maharashtra',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Manohar International Airport (GOA Airport)',
                'airport_lat' => '15.7318257',
                'airport_lng' => '73.86810799999999',
                'city_location' => 'Mopa',
                'state' => 'Goa',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Bhuj Airportf',
                'airport_lat' => '23.2755333',
                'airport_lng' => '69.66394799999999',
                'city_location' => 'Bhuj',
                'state' => 'Gujarat',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Delhi airport',
                'airport_lat' => '28.5625788',
                'airport_lng' => '77.118892',
                'city_location' => 'New Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'enable' => true,
            ],
            [
                'airport_name' => 'Raja Bhoj International Airport, Bhopal',
                'airport_lat' => '23.2909326',
                'airport_lng' => '77.3356239',
                'city_location' => 'Bhopal',
                'state' => 'Madhya Pradesh',
                'country' => 'India',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('airports')->updateOrInsert(['airport_name' => $record['airport_name']], $record);
        }
    }
}
