<?php

use Illuminate\Support\Facades\Broadcast;

// Channel parameters arrive as strings while model IDs are auto-increment
// integers, so compare loosely (cast to string) to avoid a strict-type mismatch.
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

// Shared channels for broadcasting newly-placed (unassigned) orders to all
// online drivers. Any authenticated driver may listen; the actual nearby
// filtering (radius / service / zone) still happens server-side on refresh.
Broadcast::channel('drivers.new-orders', function ($user) {
    return $user !== null;
});

Broadcast::channel('drivers.new-intercity-orders', function ($user) {
    return $user !== null;
});

// ── Return Rides ────────────────────────────────────────────────────────────
// Driver listens for new bookings on their published rides; customer listens
// for confirmations / updates on rides they booked.
Broadcast::channel('driver.{driverId}.return-rides', function ($user, $driverId) {
    return (string) $user->id === (string) $driverId;
});

Broadcast::channel('customer.{userId}.return-rides', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

// Shared channel for newly-published return rides delivered to all online
// passengers (server-side corridor / time-window filtering still applies).
Broadcast::channel('customers.new-return-rides', function ($user) {
    return $user !== null;
});
