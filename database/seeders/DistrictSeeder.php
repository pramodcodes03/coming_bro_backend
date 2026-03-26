<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'name' => 'Sangali',
                'publish' => true,
            ],
            [
                'name' => 'Bundi',
                'publish' => false,
            ],
            [
                'name' => 'Jaipur',
                'publish' => false,
            ],
            [
                'name' => 'Udaipur',
                'publish' => false,
            ],
            [
                'name' => 'Nandurbar',
                'publish' => true,
            ],
            [
                'name' => 'Bhopal',
                'publish' => false,
            ],
            [
                'name' => 'Thane',
                'publish' => true,
            ],
            [
                'name' => 'Gadchiroli',
                'publish' => true,
            ],
            [
                'name' => 'Gondia',
                'publish' => true,
            ],
            [
                'name' => 'Gandhinagar',
                'publish' => false,
            ],
            [
                'name' => 'Kaithal',
                'publish' => false,
            ],
            [
                'name' => 'New Delhi',
                'publish' => false,
            ],
            [
                'name' => 'North Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Bikaner',
                'publish' => false,
            ],
            [
                'name' => 'Chikodi',
                'publish' => true,
            ],
            [
                'name' => 'Surat',
                'publish' => false,
            ],
            [
                'name' => 'Swami Madhopur',
                'publish' => false,
            ],
            [
                'name' => 'Jalgaon',
                'publish' => true,
            ],
            [
                'name' => 'East Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Dhule',
                'publish' => true,
            ],
            [
                'name' => 'Chatrapati Sambhaji Nagar',
                'publish' => true,
            ],
            [
                'name' => 'Prayagraj',
                'publish' => false,
            ],
            [
                'name' => 'Belgaon',
                'publish' => true,
            ],
            [
                'name' => 'Ahmedabad',
                'publish' => true,
            ],
            [
                'name' => 'Amreli',
                'publish' => false,
            ],
            [
                'name' => 'Gwalior',
                'publish' => false,
            ],
            [
                'name' => 'North-west Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Lucknow',
                'publish' => false,
            ],
            [
                'name' => 'Kota',
                'publish' => false,
            ],
            [
                'name' => 'Faridabad',
                'publish' => false,
            ],
            [
                'name' => 'Sindhudurga',
                'publish' => true,
            ],
            [
                'name' => 'Pali',
                'publish' => false,
            ],
            [
                'name' => 'Ghaziabad',
                'publish' => false,
            ],
            [
                'name' => 'Jalore',
                'publish' => false,
            ],
            [
                'name' => 'Sirsa',
                'publish' => false,
            ],
            [
                'name' => 'Baghpat',
                'publish' => false,
            ],
            [
                'name' => 'Dungarpur',
                'publish' => false,
            ],
            [
                'name' => 'Nanded',
                'publish' => true,
            ],
            [
                'name' => 'Buldhana',
                'publish' => true,
            ],
            [
                'name' => 'Karnal',
                'publish' => false,
            ],
            [
                'name' => 'North-East Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Ujjain',
                'publish' => false,
            ],
            [
                'name' => 'Tonk',
                'publish' => false,
            ],
            [
                'name' => 'Sagar',
                'publish' => false,
            ],
            [
                'name' => 'Yavatmal',
                'publish' => true,
            ],
            [
                'name' => 'Belgaon',
                'publish' => false,
            ],
            [
                'name' => 'Hisar',
                'publish' => false,
            ],
            [
                'name' => 'Nagpur',
                'publish' => true,
            ],
            [
                'name' => 'Dharashiv',
                'publish' => true,
            ],
            [
                'name' => 'Hingoli',
                'publish' => true,
            ],
            [
                'name' => 'Ratnagiri',
                'publish' => true,
            ],
            [
                'name' => 'Hanumangarh',
                'publish' => false,
            ],
            [
                'name' => 'Pune',
                'publish' => true,
            ],
            [
                'name' => 'Karauli',
                'publish' => false,
            ],
            [
                'name' => 'Barmer',
                'publish' => false,
            ],
            [
                'name' => 'Kalburgi',
                'publish' => true,
            ],
            [
                'name' => 'Gorkhpur',
                'publish' => false,
            ],
            [
                'name' => 'Jind',
                'publish' => false,
            ],
            [
                'name' => 'Beed',
                'publish' => true,
            ],
            [
                'name' => 'Bulandhshahr',
                'publish' => false,
            ],
            [
                'name' => 'Jhalawar',
                'publish' => false,
            ],
            [
                'name' => 'Gurugram ',
                'publish' => false,
            ],
            [
                'name' => 'Jodhpur',
                'publish' => false,
            ],
            [
                'name' => 'Panipat',
                'publish' => false,
            ],
            [
                'name' => 'Akola',
                'publish' => true,
            ],
            [
                'name' => 'Central Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Dholpur',
                'publish' => false,
            ],
            [
                'name' => 'Rewari',
                'publish' => false,
            ],
            [
                'name' => 'Nipani',
                'publish' => true,
            ],
            [
                'name' => 'Kanpur',
                'publish' => false,
            ],
            [
                'name' => 'Jhansi',
                'publish' => false,
            ],
            [
                'name' => 'Goa',
                'publish' => true,
            ],
            [
                'name' => 'Amethi',
                'publish' => false,
            ],
            [
                'name' => 'Jhajjar',
                'publish' => false,
            ],
            [
                'name' => 'Pimpri Chinchwad',
                'publish' => true,
            ],
            [
                'name' => 'Rohtak',
                'publish' => false,
            ],
            [
                'name' => 'Fatehabad',
                'publish' => false,
            ],
            [
                'name' => 'Jabalpur',
                'publish' => false,
            ],
            [
                'name' => 'Gautam Buddh Nagar',
                'publish' => false,
            ],
            [
                'name' => 'Varanasi',
                'publish' => false,
            ],
            [
                'name' => 'Mumbai',
                'publish' => true,
            ],
            [
                'name' => 'Jaisalmer',
                'publish' => false,
            ],
            [
                'name' => 'Rajsamand',
                'publish' => false,
            ],
            [
                'name' => 'Amravati',
                'publish' => true,
            ],
            [
                'name' => 'Bhavnagar',
                'publish' => false,
            ],
            [
                'name' => 'Palwal',
                'publish' => false,
            ],
            [
                'name' => 'Nagaur',
                'publish' => false,
            ],
            [
                'name' => 'South East Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Raybagh',
                'publish' => true,
            ],
            [
                'name' => 'Sirohi',
                'publish' => false,
            ],
            [
                'name' => 'West Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Churu',
                'publish' => false,
            ],
            [
                'name' => 'Jalna',
                'publish' => true,
            ],
            [
                'name' => 'Nashik',
                'publish' => true,
            ],
            [
                'name' => 'Pratapgarh',
                'publish' => false,
            ],
            [
                'name' => 'Shahdara',
                'publish' => false,
            ],
            [
                'name' => 'Satna',
                'publish' => false,
            ],
            [
                'name' => 'Parbhani',
                'publish' => true,
            ],
            [
                'name' => 'Nizamabad Karnatak',
                'publish' => false,
            ],
            [
                'name' => 'Firozabad',
                'publish' => false,
            ],
            [
                'name' => 'Bhiwani',
                'publish' => false,
            ],
            [
                'name' => 'Vijaypura',
                'publish' => false,
            ],
            [
                'name' => 'Vadodara',
                'publish' => false,
            ],
            [
                'name' => 'Vardha',
                'publish' => true,
            ],
            [
                'name' => 'Solapur',
                'publish' => true,
            ],
            [
                'name' => 'Mahendragarh',
                'publish' => false,
            ],
            [
                'name' => 'Palghar',
                'publish' => true,
            ],
            [
                'name' => 'Chandrapur',
                'publish' => true,
            ],
            [
                'name' => 'Burhanpur',
                'publish' => true,
            ],
            [
                'name' => 'Sikar',
                'publish' => false,
            ],
            [
                'name' => 'Indore',
                'publish' => false,
            ],
            [
                'name' => 'Dausa',
                'publish' => false,
            ],
            [
                'name' => 'North-West Delhi',
                'publish' => false,
            ],
            [
                'name' => 'Satara',
                'publish' => true,
            ],
            [
                'name' => 'Jalore',
                'publish' => false,
            ],
            [
                'name' => 'Kolhapur',
                'publish' => true,
            ],
            [
                'name' => 'Yamunanagar',
                'publish' => false,
            ],
            [
                'name' => 'Meerut',
                'publish' => false,
            ],
            [
                'name' => 'Raigad',
                'publish' => true,
            ],
            [
                'name' => 'Anand',
                'publish' => false,
            ],
            [
                'name' => 'Chittorgarh',
                'publish' => false,
            ],
            [
                'name' => 'Sonipat',
                'publish' => false,
            ],
            [
                'name' => 'Hapur',
                'publish' => true,
            ],
            [
                'name' => 'Panchkula',
                'publish' => false,
            ],
            [
                'name' => 'Mathura',
                'publish' => false,
            ],
            [
                'name' => 'Ahilya Nagar',
                'publish' => true,
            ],
            [
                'name' => 'Latur',
                'publish' => true,
            ],
            [
                'name' => 'Jhunjhunu',
                'publish' => false,
            ],
            [
                'name' => 'Kota',
                'publish' => false,
            ]
        ];

        foreach ($records as $record) {
            DB::table('districts')->updateOrInsert(['name' => $record['name']], $record);
        }
    }
}
