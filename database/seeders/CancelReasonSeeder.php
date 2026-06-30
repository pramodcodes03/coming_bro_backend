<?php

namespace Database\Seeders;

use App\Models\CancelReason;
use Illuminate\Database\Seeder;

class CancelReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            // Customer-side reasons (from the cancel sheet).
            ['Driver denied to go to destination', 'customer'],
            ['Driver denied to come to pickup', 'customer'],
            ['Expected a shorter wait time', 'customer'],
            ['Unable to contact driver', 'customer'],
            ['Driver wants cash', 'customer'],
            ['Driver looked unwell', 'customer'],
            ['Car not sanitized / unhygienic', 'customer'],

            // Driver-side reasons.
            ['Customer not at pickup location', 'driver'],
            ['Unable to contact customer', 'driver'],
            ['Customer asked to cancel', 'driver'],
            ['Wrong pickup location', 'driver'],
            ['Vehicle issue / breakdown', 'driver'],
            ['Pickup too far away', 'driver'],

            // Shown to both.
            ['My reason is not listed', 'both'],
        ];

        foreach ($reasons as $i => [$reason, $appliesTo]) {
            CancelReason::updateOrCreate(
                ['reason' => $reason, 'applies_to' => $appliesTo],
                ['is_active' => true, 'sort_order' => $i + 1],
            );
        }
    }
}
