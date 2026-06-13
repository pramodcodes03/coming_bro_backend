<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('customer.{userId}.orders', function ($user, $userId) {
    return $user->id === $userId;
});

Broadcast::channel('customer.{userId}.intercity-orders', function ($user, $userId) {
    return $user->id === $userId;
});

Broadcast::channel('driver.{driverId}.orders', function ($user, $driverId) {
    return $user->id === $driverId;
});

Broadcast::channel('driver.{driverId}.intercity-orders', function ($user, $driverId) {
    return $user->id === $driverId;
});

Broadcast::channel('driver.{driverId}.location', function ($user, $driverId) {
    return true;
});
