<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'id' => '8YIeHEEeJkPMqv2asHAy',
                'name' => 'India',
                'latitude' => 36.66018513715171,
                'longitude' => 72.07767174265898,
                'area' => '[{"latitude": 36.66018513715171, "longitude": 72.07767174265898}, {"latitude": 32.092494005805406, "longitude": 74.01126549265898}, {"latitude": 28.217925652054916, "longitude": 71.37454674265898}, {"latitude": 27.82999905153852, "longitude": 68.91360924265898}, {"latitude": 25.076302086179293, "longitude": 69.96829674265898}, {"latitude": 23.473985815047183, "longitude": 67.15579674265898}, {"latitude": 7.003479957458266, "longitude": 77.43899986765898}, {"latitude": 21.688727828100415, "longitude": 91.14993736765898}, {"latitude": 19.88107041816706, "longitude": 91.85306236765898}, {"latitude": 28.372703801650328, "longitude": 98.35696861765898}, {"latitude": 30.060210297529647, "longitude": 96.24759361765898}, {"latitude": 28.350688824521438, "longitude": 92.12935246662173}, {"latitude": 28.736726516686453, "longitude": 86.06489934162173}, {"latitude": 31.022702857562045, "longitude": 81.58247746662173}, {"latitude": 31.623342605971466, "longitude": 78.94575871662173}, {"latitude": 33.98699180845734, "longitude": 80.43989934162173}, {"latitude": 36.073864119227274, "longitude": 80.61568059162173}, {"latitude": 36.073864119227274, "longitude": 77.80318059162173}, {"latitude": 37.621093127288496, "longitude": 74.99068059162173}, {"latitude": 37.13219286719327, "longitude": 71.91450871662173}]',
                'publish' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('zones')->updateOrInsert(['id' => $record['id']], $record);
        }
    }
}
