<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Retire lapsed ride lots hourly and remove their unused rides from drivers.
// (Expired lots are also filtered out lazily on every balance read, so this
// is the housekeeping pass that physically zeroes them.)
Schedule::command('rides:expire-lots')->hourly()->withoutOverlapping();
