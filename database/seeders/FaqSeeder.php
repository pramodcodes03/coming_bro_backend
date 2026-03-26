<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'title' => 'How Can I Pay ?',
                'description' => 'You can pay for your rental by Cash to The Driver. ',
                'enable' => true,
            ],
            [
                'title' => 'How is Coming Bro helpful for customers?',
                'description' => 'Coming Bro is a long term sustainable solution for the city\'s mobility requirements. Radical tech and marketing innovations will reduce overall costs and its benefits will flow to customers and drivers. And having an open roadmap will further improve the efficiency (Eg. Open maps), affordability (Eg. batching / sharing) and interoperability (Eg. integrating with public transport).',
                'enable' => true,
            ],
            [
                'title' => 'What is the driver availability across the city?',
                'description' => 'We have a good coverage of drivers across the city and the driver availability data (by location) is openly shared as part of our open-data initiative on Coming Bro website. We hope the coverage and density of drivers will improve with more awareness.',
                'enable' => true,
            ],
            [
                'title' => 'What is the monetisation strategy for Coming Bro?',
                'description' => 'The core innovation in this initiative is radically reducing the cost structure of the critical components like maps, cloud, operations & marketing. This is helping us to convert from a high commission based model to a very nominal subscription fee for the drivers, similar to a mobile recharge. Right now, the platform is free in few District and we will start charging the nominal subscription fee within a couple of months.',
                'enable' => true,
            ],
            [
                'title' => 'How Does The Coming Bro App Work?',
                'description' => 'First, the rider needs to download the Coming Bro from App Store or Play Store Sign Up with essential details (Name and phone number) Grant location permission A pop up screen will be displayed with all  the drivers available in the nearby area Enter destination and proposed fare If the driver does not accept the proposed fare a different offer may appear from the driver’s end Once the negotiation is done and the driver accepts the offer the rider can avail the services After the ride is over the rider can provide rating and review for the service',
                'enable' => true,
            ],
            [
                'title' => 'How do you handle cancellation issues?',
                'description' => 'Cancellations typically happen from both customers and drivers. As per mobility experts, driver cancellation rates are the lowest in Coming Bro. While we can\'t completely eliminate genuine cancellations, we intend to reduce it by improving the product (UI/UX, matching algorithms, in-app chat etc) and implementing a self-regulated mechanism of a trust score (both for customers and drivers) which incorporates the cancellation rates. We are also working with drivers to launch a special service for zones like Metro and hospitals where drivers commit to not cancel.',
                'enable' => true,
            ]
        ];

        foreach ($records as $record) {
            DB::table('faqs')->updateOrInsert(['title' => $record['title']], $record);
        }
    }
}
