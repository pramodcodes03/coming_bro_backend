<?php

use Illuminate\Support\Facades\Broadcast;

// NOTE: channel placeholders ({userId}/{driverId}) always arrive as strings,
// while the authenticated model's primary key is an auto-increment integer.
// Compare as strings so the strict check authorises correctly (an int ===
// string comparison is always false in PHP and would reject every subscriber).

Broadcast::channel('customer.{userId}.orders', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

Broadcast::channel('customer.{userId}.intercity-orders', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

Broadcast::channel('driver.{driverId}.orders', function ($user, $driverId) {
    return (string) $user->id === (string) $driverId;
});

Broadcast::channel('driver.{driverId}.intercity-orders', function ($user, $driverId) {
    return (string) $user->id === (string) $driverId;
});

Broadcast::channel('driver.{driverId}.location', function ($user, $driverId) {
    return true;
});
