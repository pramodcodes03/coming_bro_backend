<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * God's Eye View — the admin master control / troubleshooting console.
 *
 * Renders a single live dashboard (Google Map + driver roster + trip details)
 * and exposes a lightweight JSON feed the page polls every few seconds so the
 * map and tables stay fresh without a websocket/Echo build step.
 */
class GodsEyeController extends Controller
{
    /**
     * Statuses (as stored on the `orders` table) that represent a ride that is
     * currently happening / assigned, i.e. "active". Matched case-insensitively
     * so minor casing differences from the apps don't slip through.
     */
    private const ACTIVE_STATUSES = ['ride active', 'accepted', 'arriving', 'arrived', 'in_progress', 'ongoing', 'started'];

    private const COMPLETED_STATUSES = ['completed', 'ride completed', 'ride end', 'end ride', 'finished'];

    private const CANCELLED_STATUSES = ['ride canceled', 'ride cancelled', 'cancelled', 'canceled', 'rejected'];

    /**
     * Render the God's Eye View shell with an initial server-rendered snapshot
     * so the page is useful instantly (before the first poll returns).
     */
    public function index()
    {
        // Google Maps JS key, stored in settings → globalKey → googleMapKey.
        $mapKey = data_get(
            optional(Setting::find('globalKey'))->value,
            'googleMapKey'
        );

        return view('admin.gods-eye.index', [
            'snapshot' => $this->snapshot(),
            'mapKey' => $mapKey,
        ]);
    }

    /**
     * JSON live-feed polled by the dashboard. Returns the same structure as the
     * initial snapshot so the front-end has a single rendering path.
     */
    public function feed(): JsonResponse
    {
        return response()->json($this->snapshot());
    }

    /**
     * Full point-in-time picture of the fleet: master driver list with live
     * status & location, summary KPIs, and granular details for in-flight and
     * recent rides. This is the single source the view + feed both consume.
     */
    private function snapshot(): array
    {
        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $weekAgo = $now->copy()->subDays(7)->startOfDay();

        // ── Master driver list (all drivers, online first, then most recent) ──
        $drivers = DriverUser::query()
            ->orderByDesc('is_online')
            ->orderByDesc('last_online_at')
            ->orderByDesc('id')
            ->get();

        // Active rides keyed by driver so we can attach a driver's current trip.
        $activeRides = Order::with(['customer', 'driver'])
            ->whereIn($this->lowerStatusExpr(), self::ACTIVE_STATUSES)
            ->orderByDesc('id')
            ->get();

        $activeByDriver = $activeRides
            ->filter(fn (Order $o) => $o->driver_id !== null)
            ->keyBy('driver_id');

        $driverPayload = $drivers->map(function (DriverUser $d) use ($now, $activeByDriver) {
            $online = (bool) $d->is_online;
            $onlineSince = $d->last_online_at;
            $lat = $d->location_latitude ?? $d->position_latitude;
            $lng = $d->location_longitude ?? $d->position_longitude;
            $ride = $activeByDriver->get($d->id);

            return [
                'id' => $d->id,
                'name' => $d->full_name ?: 'Driver #'.$d->id,
                'phone' => $d->phone_number,
                'avatar' => storage_url($d->profile_pic),
                'initials' => $this->initials($d->full_name),
                'is_online' => $online,
                'verified' => (bool) $d->document_verification,
                'vehicle_number' => $d->vehicle_number,
                'vehicle_model' => trim(($d->company_name ?? '').' '.($d->vehicle_model ?? '')) ?: null,
                'city' => $d->city,
                'rating' => $this->rating($d),
                'wallet' => (float) $d->wallet_amount,
                'lat' => $lat !== null ? (float) $lat : null,
                'lng' => $lng !== null ? (float) $lng : null,
                'rotation' => $d->rotation !== null ? (float) $d->rotation : 0,
                'has_location' => $lat !== null && $lng !== null,
                'online_since' => $onlineSince?->toIso8601String(),
                'online_since_human' => $online && $onlineSince ? $onlineSince->diffForHumans($now, true) : null,
                'online_since_label' => $online && $onlineSince ? $onlineSince->format('d M, h:i A') : null,
                'last_seen_human' => $d->updated_at?->diffForHumans($now, true),
                'on_trip' => $ride !== null,
                'current_trip' => $ride ? $this->tripPayload($ride) : null,
            ];
        })->values();

        // ── Recent trips (last 7 days) with granular pickup/drop/passenger ──
        $recentTrips = Order::with(['customer', 'driver'])
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->map(fn (Order $o) => $this->tripPayload($o))
            ->values();

        // ── KPIs ──
        $onlineCount = $drivers->where('is_online', true)->count();
        $todayOnlineCount = $drivers->filter(
            fn (DriverUser $d) => $d->last_online_at && $d->last_online_at->gte($todayStart)
        )->count();

        $activeTripCount = $activeRides->count();
        $todayTripCount = Order::whereNotNull('created_date')
            ->where('created_date', '>=', $todayStart)
            ->count();
        $todayCompletedCount = Order::whereIn($this->lowerStatusExpr(), self::COMPLETED_STATUSES)
            ->whereNotNull('created_date')
            ->where('created_date', '>=', $todayStart)
            ->count();

        // Inactive: not online and no online activity in the last 7 days.
        $inactiveCount = $drivers->filter(function (DriverUser $d) use ($weekAgo) {
            if ($d->is_online) {
                return false;
            }

            return $d->last_online_at === null || $d->last_online_at->lt($weekAgo);
        })->count();

        return [
            'generated_at' => $now->toIso8601String(),
            'generated_at_label' => $now->format('h:i:s A'),
            'kpis' => [
                'total_drivers' => $drivers->count(),
                'online_drivers' => $onlineCount,
                'offline_drivers' => $drivers->count() - $onlineCount,
                'online_today' => $todayOnlineCount,
                'inactive_drivers' => $inactiveCount,
                'active_trips' => $activeTripCount,
                'today_trips' => $todayTripCount,
                'today_completed' => $todayCompletedCount,
                'drivers_on_trip' => $activeByDriver->count(),
            ],
            'drivers' => $driverPayload,
            'active_trips' => $activeRides->map(fn (Order $o) => $this->tripPayload($o))->values(),
            'recent_trips' => $recentTrips,
        ];
    }

    /**
     * Granular detail for one ride: pickup, drop-off, last-known live location,
     * the passenger (incl. "someone else" booked-for details), driver and
     * money/distance — everything an admin needs to troubleshoot a trip.
     */
    private function tripPayload(Order $o): array
    {
        $bucket = $this->statusBucket($o->status);
        $someoneElse = is_array($o->some_one_else) ? $o->some_one_else : [];

        return [
            'id' => $o->id,
            'status' => $o->status,
            'status_label' => $o->status ?: 'Unknown',
            'status_bucket' => $bucket,
            'pickup' => $o->source_location_name,
            'pickup_lat' => $o->source_latitude !== null ? (float) $o->source_latitude : null,
            'pickup_lng' => $o->source_longitude !== null ? (float) $o->source_longitude : null,
            'drop' => $o->destination_location_name,
            'drop_lat' => $o->destination_latitude !== null ? (float) $o->destination_latitude : null,
            'drop_lng' => $o->destination_longitude !== null ? (float) $o->destination_longitude : null,
            'live_lat' => $o->position_latitude !== null ? (float) $o->position_latitude : null,
            'live_lng' => $o->position_longitude !== null ? (float) $o->position_longitude : null,
            'has_live_location' => $o->position_latitude !== null && $o->position_longitude !== null,
            'fare' => $o->final_rate !== null ? (float) $o->final_rate : ($o->offer_rate !== null ? (float) $o->offer_rate : null),
            'payment_type' => $o->payment_type,
            'payment_status' => (bool) $o->payment_status,
            'distance' => $this->prettyDistance($o->distance, $o->distance_type),
            'duration' => $o->duration,
            'created_label' => $o->created_date?->format('d M Y, h:i A'),
            'created_human' => $o->created_date?->diffForHumans(),
            'passenger' => [
                'name' => $o->customer?->full_name,
                'phone' => $o->customer?->phone_number,
                'avatar' => storage_url($o->customer?->profile_pic),
                'initials' => $this->initials($o->customer?->full_name),
                'booked_for_someone_else' => ! empty($someoneElse),
                'booked_for_name' => data_get($someoneElse, 'name') ?? data_get($someoneElse, 'full_name'),
                'booked_for_phone' => data_get($someoneElse, 'phone') ?? data_get($someoneElse, 'phone_number'),
            ],
            'driver' => $o->driver ? [
                'id' => $o->driver->id,
                'name' => $o->driver->full_name ?: 'Driver #'.$o->driver->id,
                'phone' => $o->driver->phone_number,
                'vehicle_number' => $o->driver->vehicle_number,
                'initials' => $this->initials($o->driver->full_name),
            ] : null,
        ];
    }

    /**
     * Map a raw status string to a coarse bucket the UI styles consistently.
     */
    private function statusBucket(?string $status): string
    {
        $s = strtolower(trim((string) $status));

        return match (true) {
            in_array($s, self::COMPLETED_STATUSES, true) => 'completed',
            in_array($s, self::CANCELLED_STATUSES, true) => 'cancelled',
            in_array($s, self::ACTIVE_STATUSES, true) => 'active',
            // "Ride Placed" and any other waiting/unknown state fall through here.
            default => 'placed',
        };
    }

    /**
     * Raw SQL expression that lower-cases & trims the status column so the
     * whereIn comparisons above are case-insensitive without a DB collation
     * assumption.
     */
    private function lowerStatusExpr(): Expression
    {
        return DB::raw('LOWER(TRIM(status))');
    }

    private function prettyDistance(?string $distance, ?string $type): ?string
    {
        if ($distance === null || $distance === '') {
            return null;
        }

        $value = round((float) $distance, 1);

        return $value.' '.($type ?: 'km');
    }

    private function rating(DriverUser $d): float
    {
        $count = (float) $d->reviews_count;
        $sum = (float) $d->reviews_sum;

        if ($count <= 0) {
            return 0.0;
        }

        return round($sum / $count, 1);
    }

    private function initials(?string $name): string
    {
        $name = trim((string) $name);

        if ($name === '') {
            return '?';
        }

        $parts = preg_split('/\s+/', $name);
        $first = mb_substr($parts[0], 0, 1);
        $second = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '';

        return mb_strtoupper($first.$second);
    }
}
