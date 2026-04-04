<?php

namespace Database\Seeders;

use App\Models\RechargePlan;
use Illuminate\Database\Seeder;

class RechargePlanSeeder extends Seeder
{
    public function run(): void
    {
        RechargePlan::updateOrCreate(
            ['label' => 'Daily Ride (Regular)'],
            [
                'price' => 49,
                'original_price' => 99,
                'discount_pct' => 50,
                'is_best_value' => false,
                'is_active' => true,
                'sort_order' => 1,
                'benefits' => [
                    [
                        'icon' => 'directions_car_rounded',
                        'title' => '10 Rides',
                        'subtitle' => 'Accept & complete up to 10 rides',
                    ],
                    [
                        'icon' => 'all_inclusive_rounded',
                        'title' => 'No Expiry',
                        'subtitle' => 'Valid until all 10 rides are used',
                    ],
                    [
                        'icon' => 'local_offer_rounded',
                        'title' => 'Launch Price – ₹49',
                        'subtitle' => 'Regular price ₹99 — 50% off, limited time',
                    ],
                ],
                'terms_title' => '10 Rides Launch Offer – Terms & Conditions',
                'terms_points' => [
                    'The 10 Rides Plan allows drivers to accept and complete up to 10 rides through the Coming Bro Rider platform once the service becomes active.',
                    'The standard price of this plan is ₹99. As part of a limited-time launch promotional offer, drivers can purchase the plan for ₹49.',
                    'This plan is part of a pre-launch / early access offer. The ride service may not be available immediately after recharge and will begin once the platform service becomes active in the driver\'s service area.',
                    'The 10 rides will be available for use only after the service is officially launched in the driver\'s city or location.',
                    'The plan has no time limit or expiry date and will remain valid until all 10 rides are completed after the service launch.',
                    'Each successfully completed ride will deduct one ride from the driver\'s remaining ride balance.',
                    'Cancelled or incomplete rides will not be counted toward the ride limit.',
                    'The recharge amount paid for this promotional plan is non-refundable, except where required by applicable law.',
                    'Coming Bro Rider reserves the right to delay, modify, or update the launch timeline of the service due to operational or technical reasons.',
                    'Any misuse of the platform, fraudulent activity, or violation of company policies may lead to suspension or termination of the driver account or plan benefits without refund.',
                    'Coming Bro Rider reserves the right to modify or update these Terms & Conditions at any time.',
                    'These terms are governed by the laws of India, and any disputes shall be subject to the jurisdiction of the appropriate courts.',
                ],
                'terms_footer' => 'By purchasing this plan, the driver confirms that they have read, understood, and agreed to these Terms & Conditions.',
            ]
        );

        RechargePlan::updateOrCreate(
            ['label' => 'Return Ride (Ride while returning)'],
            [
                'price' => 99,
                'original_price' => 199,
                'discount_pct' => 50,
                'is_best_value' => true,
                'is_active' => true,
                'sort_order' => 2,
                'benefits' => [
                    [
                        'icon' => 'loop_rounded',
                        'title' => 'Unlimited Return Rides',
                        'subtitle' => 'Pick passengers on your way back',
                    ],
                    [
                        'icon' => 'calendar_month_rounded',
                        'title' => '3 Months Validity',
                        'subtitle' => 'Starts from the date feature goes live',
                    ],
                    [
                        'icon' => 'people_alt_rounded',
                        'title' => 'Earn While Returning',
                        'subtitle' => 'Rides assigned on your return route',
                    ],
                    [
                        'icon' => 'star_rounded',
                        'title' => 'Early Access Priority',
                        'subtitle' => 'Priority access when feature launches',
                    ],
                ],
                'terms_title' => 'Return Ride Feature – Early Access Terms & Conditions',
                'terms_points' => [
                    'The ₹99 recharge is a promotional Early Access offer for the upcoming Return Ride feature in the Coming Bro Rider Driver Application.',
                    'The standard price of the Return Ride plan is ₹199. The current ₹99 recharge is a limited-time promotional offer available before the official launch of the feature.',
                    'The Return Ride feature may not be available immediately after recharge. The feature will be activated once it is officially launched in the driver\'s service area or city.',
                    'The 3-month validity period will begin only from the date when the Return Ride feature becomes active in the driver\'s location.',
                    'By completing the recharge, the driver acknowledges and agrees that the feature will be activated at a later date and not necessarily immediately after payment.',
                    'The ₹99 recharge is a promotional activation fee and is non-refundable, except in cases where required by applicable law.',
                    'Coming Bro Rider reserves the right to modify, delay, or update the launch timeline of the Return Ride feature based on operational, technical, or regulatory requirements.',
                    'Drivers who complete the Early Access recharge may receive priority access or early availability when the feature becomes live.',
                    'Misuse of the Return Ride feature, fraudulent activity, or violation of platform policies may lead to suspension or termination of access without refund.',
                    'Coming Bro Rider reserves the right to update these Terms & Conditions at any time. Continued use of the service will indicate acceptance of the updated terms.',
                    'These terms are governed by the laws of India, and any disputes shall be subject to the jurisdiction of the appropriate courts.',
                ],
                'terms_footer' => 'By proceeding with the recharge, the driver confirms that they have read, understood, and agreed to these Terms & Conditions.',
            ]
        );
    }
}
