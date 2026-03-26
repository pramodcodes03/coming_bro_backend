<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OnBoardingSeeder extends Seeder
{
    public function run(): void
    {
        // Seed legacy on_boardings table
        $legacyRecords = [
            [
                'id' => '3UCNMT0kM5FbjsY3lN2K',
                'title' => 'Select Destination',
                'description' => 'Simply enter your pickup and drop-off locations, set your destination, and you\'ll have a vehicle ready in minutes.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON_1737623066585.png?alt=media&token=82afd7bb-5cf9-43a9-8bd0-8ca854341364',
                'type' => 'customerApp',
            ],
            [
                'id' => 'FWUnvoxPthtPr0frG4Vl',
                'title' => 'Accept Request',
                'description' => 'Collect the OTP from the passenger and get permission to start the ride smoothly.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FAccept%20Request_1735795023223.png?alt=media&token=9c6c990c-7711-439f-b018-ca6c4549c8c8',
                'type' => 'driverApp',
            ],
            [
                'id' => 'IQYL93uynd1p0YJWKHlF',
                'title' => 'Check Fare & Book a Ride',
                'description' => 'Before your ride, you can review the fare and approve it to start the journey.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(2)_1737623356125.png?alt=media&token=4e645ee1-8737-4c6e-b8cd-c4d5784dcf2d',
                'type' => 'customerApp',
            ],
            [
                'id' => 'aD4FyhjDAJvywHmN2R4h',
                'title' => 'Tracking Realtime',
                'description' => 'Seamless and real time communication between Drivers & Customers. Provide fast reliable and comprehensive information to our Drivers & Customers',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2Flive%20tracking%202_1735821695002.png?alt=media&token=3bbcd6bd-e9da-44a8-9b39-c3124f507dae',
                'type' => 'driverApp',
            ],
            [
                'id' => 'iuAxOEUekDVxlazILobd',
                'title' => 'Enjoy your trip!',
                'description' => 'This is beneficial for passengers as they have more vehicle options nearby, no cancellation charges, the ability to negotiate rates, and the freedom to choose from available options without any hassle.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(3)_1737623611225.png?alt=media&token=9a2ff8b7-0880-4e9c-b2fc-813801d6a087',
                'type' => 'customerApp',
            ],
            [
                'id' => 'yruOL91CxCPpTCofM5jA',
                'title' => 'Earn Money',
                'description' => 'Business opportunities are like buses—there\'s always another one on the way." And here we are! "USE EARN GROW.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(5)_1737623969223.png?alt=media&token=827ea6f2-864a-4499-9dfb-7f94a42ff6ec',
                'type' => 'driverApp',
            ]
        ];

        foreach ($legacyRecords as $record) {
            DB::table('on_boardings')->updateOrInsert(['id' => $record['id']], $record);
        }

        // Seed onboarding_screens table (used by the API controller)
        $screens = [
            [
                'id' => 'FWUnvoxPthtPr0frG4Vl',
                'title' => 'Accept Request',
                'description' => 'Collect the OTP from the passenger and get permission to start the ride smoothly.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FAccept%20Request_1735795023223.png?alt=media&token=9c6c990c-7711-439f-b018-ca6c4549c8c8',
                'type' => 'driverApp',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 'aD4FyhjDAJvywHmN2R4h',
                'title' => 'Tracking Realtime',
                'description' => 'Seamless and real time communication between Drivers & Customers. Provide fast reliable and comprehensive information to our Drivers & Customers',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2Flive%20tracking%202_1735821695002.png?alt=media&token=3bbcd6bd-e9da-44a8-9b39-c3124f507dae',
                'type' => 'driverApp',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 'yruOL91CxCPpTCofM5jA',
                'title' => 'Earn Money',
                'description' => 'Business opportunities are like buses—there\'s always another one on the way." And here we are! "USE EARN GROW.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(5)_1737623969223.png?alt=media&token=827ea6f2-864a-4499-9dfb-7f94a42ff6ec',
                'type' => 'driverApp',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'id' => '3UCNMT0kM5FbjsY3lN2K',
                'title' => 'Select Destination',
                'description' => 'Simply enter your pickup and drop-off locations, set your destination, and you\'ll have a vehicle ready in minutes.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON_1737623066585.png?alt=media&token=82afd7bb-5cf9-43a9-8bd0-8ca854341364',
                'type' => 'customerApp',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 'IQYL93uynd1p0YJWKHlF',
                'title' => 'Check Fare & Book a Ride',
                'description' => 'Before your ride, you can review the fare and approve it to start the journey.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(2)_1737623356125.png?alt=media&token=4e645ee1-8737-4c6e-b8cd-c4d5784dcf2d',
                'type' => 'customerApp',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 'iuAxOEUekDVxlazILobd',
                'title' => 'Enjoy your trip!',
                'description' => 'This is beneficial for passengers as they have more vehicle options nearby, no cancellation charges, the ability to negotiate rates, and the freedom to choose from available options without any hassle.',
                'image' => 'https://firebasestorage.googleapis.com/v0/b/coming-bro.appspot.com/o/on-boarding%2FCOB%20VEHICLE%20ICON%20(3)_1737623611225.png?alt=media&token=9a2ff8b7-0880-4e9c-b2fc-813801d6a087',
                'type' => 'customerApp',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($screens as $screen) {
            DB::table('onboarding_screens')->updateOrInsert(['id' => $screen['id']], $screen);
        }
    }
}
