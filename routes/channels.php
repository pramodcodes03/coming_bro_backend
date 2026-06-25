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

// ── Return Rides (scheduled-ride bidding) ───────────────────────────────────
// Customer listens for incoming driver offers / status updates on rides they
// posted; the assigned driver listens for accept/reject + ride updates.
Broadcast::channel('customer.{userId}.return-rides', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

Broadcast::channel('driver.{driverId}.return-rides', function ($user, $driverId) {
    return (string) $user->id === (string) $driverId;
});

// Shared channel for newly-posted scheduled return rides delivered to all
// online drivers (recharge gating + filtering still applies server-side).
Broadcast::channel('drivers.new-return-rides', function ($user) {
    return $user !== null;
});
