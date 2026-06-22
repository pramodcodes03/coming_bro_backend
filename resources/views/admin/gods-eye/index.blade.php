<x-layout.admin>
    {{--
        God's Eye View — live fleet master control & troubleshooting console.

        Single Alpine component (`godsEye`) owns all state. It hydrates from the
        server-rendered snapshot, then polls `admin.gods-eye.feed` every 3s and
        re-renders the KPIs, map markers, driver roster and trip details. Google
        Maps is loaded with the key stored in the `settings` table.
    --}}
    <div
        x-data="godsEye({
            feedUrl: '{{ route('admin.gods-eye.feed') }}',
            hasMapKey: {{ $mapKey ? 'true' : 'false' }},
            initial: {{ Illuminate\Support\Js::from($snapshot) }}
        })"
        x-init="init()"
        class="space-y-6"
    >

        {{-- ── Header ── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                    <svg class="h-7 w-7 text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" fill="currentColor"/>
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" fill="currentColor"/>
                    </svg>
                    God's Eye View
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Live master control for the entire fleet — track any driver, ride or passenger instantly.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Live / stale / paused pill --}}
                <button type="button" @click="togglePolling()"
                    class="flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition"
                    :class="!polling ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' : (stale ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success')"
                    :title="stale ? 'Last update failed — showing data from ' + data.generated_at_label : ''">
                    <span class="relative flex h-2.5 w-2.5">
                        <span x-show="polling && !stale" class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full" :class="!polling ? 'bg-gray-400' : (stale ? 'bg-warning' : 'bg-success')"></span>
                    </span>
                    <span x-text="!polling ? 'Paused' : (stale ? 'Reconnecting…' : 'Live')"></span>
                </button>
                <div class="hidden items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm dark:bg-[#0e1726] dark:text-gray-300 sm:flex">
                    <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Updated <span x-text="data.generated_at_label"></span></span>
                </div>
            </div>
        </div>

        {{-- ── KPI strip ── --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 xl:grid-cols-7">
            <template x-for="kpi in kpiCards" :key="kpi.key">
                <div class="panel !p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" :class="kpi.iconWrap">
                            <span x-html="kpi.icon"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xl font-bold leading-tight text-gray-900 dark:text-white" x-text="kpi.value"></p>
                            <p class="truncate text-[11px] font-medium text-gray-500 dark:text-gray-400" :title="kpi.label" x-text="kpi.label"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- ── Main grid: map + driver roster ── --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

            {{-- Live map --}}
            <div class="panel xl:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Live Fleet Map</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Online drivers and in-progress rides, refreshed every 3s</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-success"></span>Driver</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-info"></span>On trip</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-primary"></span>Pickup</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-danger"></span>Drop</span>
                    </div>
                </div>

                {{-- Map container --}}
                <div class="relative">
                    <div id="godsEyeMap" class="h-[460px] w-full overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800"></div>

                    {{-- No-key fallback --}}
                    <template x-if="!hasMapKey">
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 rounded-xl bg-gray-50 text-center dark:bg-gray-800">
                            <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/></svg>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-300">Google Maps key not configured</p>
                            <p class="max-w-xs text-xs text-gray-400">Set <code>googleMapKey</code> in Settings → globalKey to enable the live map. The roster &amp; trip data below still work.</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Driver roster --}}
            <div class="panel flex flex-col">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Driver Roster</h2>
                    <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">
                        <span x-text="data.kpis.online_drivers"></span> / <span x-text="data.kpis.total_drivers"></span> online
                    </span>
                </div>

                {{-- Filter tabs --}}
                <div class="mb-3 flex gap-1 rounded-lg bg-gray-100 p-1 dark:bg-gray-800">
                    <template x-for="tab in ['all','online','on trip','offline']" :key="tab">
                        <button type="button" @click="rosterFilter = tab"
                            class="flex-1 rounded-md py-1.5 text-xs font-semibold capitalize transition"
                            :class="rosterFilter === tab ? 'bg-white text-primary shadow-sm dark:bg-[#0e1726]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                            x-text="tab"></button>
                    </template>
                </div>

                {{-- Search --}}
                <div class="relative mb-3">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" x-model="rosterSearch" placeholder="Search driver, phone, vehicle…" class="form-input !py-2 pl-9 text-sm">
                </div>

                {{-- List --}}
                <div class="-mx-2 flex-1 space-y-1.5 overflow-y-auto px-2" style="max-height: 520px;">
                    <template x-for="d in filteredDrivers" :key="d.id">
                        <div @click="focusDriver(d)"
                            class="group flex cursor-pointer items-center gap-3 rounded-xl border border-transparent p-2.5 transition hover:border-primary/30 hover:bg-primary/5"
                            :class="selectedDriverId === d.id ? 'border-primary/40 bg-primary/5' : ''">
                            {{-- Avatar + status dot --}}
                            <div class="relative shrink-0">
                                <template x-if="d.avatar">
                                    <img :src="d.avatar" :alt="d.name" class="h-10 w-10 rounded-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                </template>
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary to-secondary text-xs font-bold text-white"
                                     :style="d.avatar ? 'display:none' : ''" x-text="d.initials"></div>
                                <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white dark:border-[#0e1726]"
                                      :class="d.is_online ? (d.on_trip ? 'bg-info' : 'bg-success') : 'bg-gray-300 dark:bg-gray-600'"></span>
                            </div>
                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white" x-text="d.name"></p>
                                    <template x-if="d.verified">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-info" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </template>
                                </div>
                                <p class="truncate text-xs text-gray-500" x-text="d.vehicle_number || d.phone || '—'"></p>
                            </div>
                            {{-- Status --}}
                            <div class="shrink-0 text-right">
                                <template x-if="d.on_trip">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-info/10 px-2 py-0.5 text-[10px] font-bold text-info">On trip</span>
                                </template>
                                <template x-if="d.is_online && !d.on_trip">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-success/10 px-2 py-0.5 text-[10px] font-bold text-success">Online</span>
                                </template>
                                <template x-if="!d.is_online">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-500 dark:bg-gray-800">Offline</span>
                                </template>
                                <p class="mt-1 text-[10px] text-gray-400" x-text="d.is_online ? (d.online_since_human ? d.online_since_human + ' online' : '') : (d.last_seen_human ? d.last_seen_human + ' ago' : '')"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredDrivers.length === 0" class="py-10 text-center text-sm text-gray-400">No drivers match.</div>
                </div>
            </div>
        </div>

        {{-- ── Active trips + selected detail ── --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

            {{-- Active trips --}}
            <div class="panel xl:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Active Trips</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rides currently in progress — click to inspect on the map</p>
                    </div>
                    <span class="rounded-full bg-info/10 px-2.5 py-0.5 text-xs font-bold text-info"><span x-text="data.active_trips.length"></span> active</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th class="w-16">#ID</th>
                                <th>Driver</th>
                                <th>Passenger</th>
                                <th>Route</th>
                                <th class="text-center">Status</th>
                                <th class="text-right">Fare</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="t in data.active_trips" :key="t.id">
                                <tr class="cursor-pointer" @click="focusTrip(t)">
                                    <td><span class="font-bold text-primary" x-text="'#' + t.id"></span></td>
                                    <td>
                                        <template x-if="t.driver">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-success/10 text-[10px] font-bold text-success" x-text="t.driver.initials"></div>
                                                <span class="whitespace-nowrap text-sm font-medium" x-text="t.driver.name"></span>
                                            </div>
                                        </template>
                                        <template x-if="!t.driver"><span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500 dark:bg-gray-800">Unassigned</span></template>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-info/10 text-[10px] font-bold text-info" x-text="t.passenger.initials"></div>
                                            <span class="whitespace-nowrap text-sm font-medium" x-text="t.passenger.name || 'N/A'"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-[220px]">
                                            <p class="truncate text-xs text-gray-500" :title="t.pickup" x-text="t.pickup || '—'"></p>
                                            <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300" :title="t.drop" x-text="t.drop || '—'"></p>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold" :class="statusClass(t.status_bucket)">
                                            <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(t.status_bucket)"></span>
                                            <span x-text="t.status_label"></span>
                                        </span>
                                    </td>
                                    <td class="text-right"><span class="font-bold text-gray-900 dark:text-white" x-text="t.fare !== null ? '₹' + formatNum(t.fare) : '—'"></span></td>
                                </tr>
                            </template>
                            <tr x-show="data.active_trips.length === 0">
                                <td colspan="6" class="py-10 text-center text-sm text-gray-400">No active trips right now.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Selected detail card --}}
            <div class="panel">
                <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white" x-text="detail ? (detail.kind === 'driver' ? 'Driver Detail' : 'Trip Detail') : 'Inspector'"></h2>

                {{-- Empty state --}}
                <div x-show="!detail" class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="mb-3 h-12 w-12 text-gray-200 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    <p class="text-sm text-gray-400">Select a driver or trip to inspect their live location, route and details here.</p>
                </div>

                {{-- Driver detail --}}
                <template x-if="detail && detail.kind === 'driver'">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-primary to-secondary text-lg font-bold text-white" x-text="detail.data.initials"></div>
                            <div class="min-w-0">
                                <p class="truncate text-base font-bold text-gray-900 dark:text-white" x-text="detail.data.name"></p>
                                <p class="text-xs text-gray-500" x-text="detail.data.phone || 'No phone'"></p>
                            </div>
                            <span class="ltr:ml-auto rtl:mr-auto rounded-full px-2.5 py-1 text-xs font-bold"
                                  :class="detail.data.is_online ? 'bg-success/10 text-success' : 'bg-gray-100 text-gray-500 dark:bg-gray-800'"
                                  x-text="detail.data.is_online ? 'Online' : 'Offline'"></span>
                        </div>
                        <dl class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Online since</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.online_since_label || '—'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Last seen</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.last_seen_human ? detail.data.last_seen_human + ' ago' : '—'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Vehicle</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.vehicle_number || '—'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Rating</dt><dd class="font-semibold text-gray-800 dark:text-gray-200"><span x-text="detail.data.rating || '—'"></span> ★</dd></div>
                            <div class="col-span-2 rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Live location</dt><dd class="font-mono text-xs font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.has_location ? (detail.data.lat.toFixed(5) + ', ' + detail.data.lng.toFixed(5)) : 'No GPS fix'"></dd></div>
                        </dl>
                        <template x-if="detail.data.current_trip">
                            <div class="rounded-xl border border-info/30 bg-info/5 p-3">
                                <p class="mb-1 text-xs font-bold uppercase text-info">Current trip · #<span x-text="detail.data.current_trip.id"></span></p>
                                <p class="text-xs text-gray-500" x-text="detail.data.current_trip.pickup"></p>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300" x-text="detail.data.current_trip.drop"></p>
                                <button type="button" class="btn btn-sm btn-outline-info mt-2 w-full" @click="focusTrip(detail.data.current_trip)">Inspect trip</button>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Trip detail --}}
                <template x-if="detail && detail.kind === 'trip'">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-primary" x-text="'#' + detail.data.id"></span>
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold" :class="statusClass(detail.data.status_bucket)">
                                <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(detail.data.status_bucket)"></span>
                                <span x-text="detail.data.status_label"></span>
                            </span>
                        </div>

                        {{-- Route timeline --}}
                        <div class="space-y-0">
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <span class="mt-1 h-3 w-3 rounded-full bg-primary ring-4 ring-primary/15"></span>
                                    <span class="my-1 w-px flex-1 bg-gray-200 dark:bg-gray-700"></span>
                                </div>
                                <div class="pb-3"><p class="text-[11px] font-bold uppercase text-gray-400">Pickup</p><p class="text-sm text-gray-700 dark:text-gray-300" x-text="detail.data.pickup || '—'"></p></div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center"><span class="h-3 w-3 rounded-full bg-danger ring-4 ring-danger/15"></span></div>
                                <div><p class="text-[11px] font-bold uppercase text-gray-400">Drop-off</p><p class="text-sm text-gray-700 dark:text-gray-300" x-text="detail.data.drop || '—'"></p></div>
                            </div>
                        </div>

                        {{-- Passenger --}}
                        <div class="rounded-xl bg-gray-50 p-3 dark:bg-white/[0.03]">
                            <p class="mb-2 text-[11px] font-bold uppercase text-gray-400">Passenger dropped off / on board</p>
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-info/10 text-xs font-bold text-info" x-text="detail.data.passenger.initials"></div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.passenger.name || 'N/A'"></p>
                                    <p class="text-xs text-gray-500" x-text="detail.data.passenger.phone || 'No phone'"></p>
                                </div>
                            </div>
                            <template x-if="detail.data.passenger.booked_for_someone_else">
                                <p class="mt-2 rounded-md bg-warning/10 px-2 py-1 text-xs text-warning">
                                    Booked for: <span class="font-semibold" x-text="detail.data.passenger.booked_for_name || 'Someone else'"></span>
                                    <span x-show="detail.data.passenger.booked_for_phone" x-text="' · ' + detail.data.passenger.booked_for_phone"></span>
                                </p>
                            </template>
                        </div>

                        {{-- Meta --}}
                        <dl class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Fare</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.fare !== null ? '₹' + formatNum(detail.data.fare) : '—'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Distance</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.distance || '—'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Driver</dt><dd class="truncate font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.driver ? detail.data.driver.name : 'Unassigned'"></dd></div>
                            <div class="rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Placed</dt><dd class="font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.created_label || '—'"></dd></div>
                            <div class="col-span-2 rounded-lg bg-gray-50 p-3 dark:bg-white/[0.03]"><dt class="text-[11px] text-gray-400">Last known live location</dt><dd class="font-mono text-xs font-semibold text-gray-800 dark:text-gray-200" x-text="detail.data.has_live_location ? (detail.data.live_lat.toFixed(5) + ', ' + detail.data.live_lng.toFixed(5)) : 'Not available'"></dd></div>
                        </dl>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Recent activity (historical) ── --}}
        <div class="panel">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Trip Activity</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Latest rides with pickup, drop-off &amp; passenger — for quick troubleshooting</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">All Orders</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th class="w-16">#ID</th>
                            <th>Driver</th>
                            <th>Passenger</th>
                            <th>Pickup → Drop</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Fare</th>
                            <th>When</th>
                            <th class="text-center w-20">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="t in data.recent_trips" :key="t.id">
                            <tr>
                                <td><span class="font-bold text-primary" x-text="'#' + t.id"></span></td>
                                <td><span class="whitespace-nowrap text-sm" x-text="t.driver ? t.driver.name : '—'"></span></td>
                                <td><span class="whitespace-nowrap text-sm" x-text="t.passenger.name || 'N/A'"></span></td>
                                <td>
                                    <div class="max-w-[260px]">
                                        <p class="truncate text-xs text-gray-500" :title="t.pickup" x-text="t.pickup || '—'"></p>
                                        <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300" :title="t.drop" x-text="t.drop || '—'"></p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold" :class="statusClass(t.status_bucket)">
                                        <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(t.status_bucket)"></span>
                                        <span x-text="t.status_label"></span>
                                    </span>
                                </td>
                                <td class="text-right"><span class="font-bold text-gray-900 dark:text-white" x-text="t.fare !== null ? '₹' + formatNum(t.fare) : '—'"></span></td>
                                <td class="whitespace-nowrap text-xs text-gray-500" x-text="t.created_human || '—'"></td>
                                <td class="text-center"><button type="button" class="text-xs font-semibold text-primary hover:underline" @click="focusTrip(t)">Inspect</button></td>
                            </tr>
                        </template>
                        <tr x-show="data.recent_trips.length === 0">
                            <td colspan="8" class="py-10 text-center text-sm text-gray-400">No recent trips.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── God's Eye Alpine component ── --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('godsEye', (cfg) => ({
                feedUrl: cfg.feedUrl,
                hasMapKey: cfg.hasMapKey,
                data: cfg.initial,
                polling: true,
                stale: false,       // true when the last poll failed (data is no longer fresh)
                pollTimer: null,
                rosterFilter: 'all',
                rosterSearch: '',
                selectedDriverId: null,
                detail: null,
                map: null,
                mapReady: false,
                markers: {},      // driver markers keyed by id
                tripMarkers: [],  // transient pickup/drop/live markers + route line

                init() {
                    if (this.hasMapKey) {
                        this.loadGoogleMaps();
                    }
                    this.startPolling();
                    // Stop polling when the tab is hidden; resume on return.
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) { this.stopPolling(); }
                        else if (this.polling) { this.startPolling(); this.refresh(); }
                    });
                },

                /* ── KPI cards (derived) ── */
                get kpiCards() {
                    const k = this.data.kpis;
                    const ic = (path) => `<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">${path}</svg>`;
                    return [
                        { key: 'total',    value: k.total_drivers,   label: 'Total Drivers',   iconWrap: 'bg-primary/10 text-primary',   icon: ic('<path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/>') },
                        { key: 'online',   value: k.online_drivers,  label: 'Online Now',      iconWrap: 'bg-success/10 text-success',   icon: ic('<path d="M12 21a9 9 0 100-18 9 9 0 000 18z"/><path d="M9 12l2 2 4-4"/>') },
                        { key: 'ontrip',   value: k.drivers_on_trip, label: 'On Trip',         iconWrap: 'bg-info/10 text-info',         icon: ic('<path d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75M2.25 14.25h1.5m13.5 0h1.5m-16.5 0V6.375c0-.621.504-1.125 1.125-1.125h3.026a3 3 0 012.25 1.016l.272.341a3 3 0 002.25 1.016h5.577c.621 0 1.125.504 1.125 1.125V14.25"/>') },
                        { key: 'offline',  value: k.offline_drivers, label: 'Offline',         iconWrap: 'bg-gray-200 text-gray-500 dark:bg-gray-700', icon: ic('<path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>') },
                        { key: 'today',    value: k.online_today,    label: 'Came Online Today', iconWrap: 'bg-secondary/10 text-secondary', icon: ic('<path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V11.25A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>') },
                        { key: 'active',   value: k.active_trips,    label: 'Active Trips',    iconWrap: 'bg-warning/10 text-warning',   icon: ic('<path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>') },
                        { key: 'inactive', value: k.inactive_drivers,label: 'Inactive (7d+)',  iconWrap: 'bg-danger/10 text-danger',     icon: ic('<path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>') },
                    ];
                },

                /* ── Roster filtering ── */
                get filteredDrivers() {
                    const q = this.rosterSearch.trim().toLowerCase();
                    return this.data.drivers.filter(d => {
                        if (this.rosterFilter === 'online' && !d.is_online) return false;
                        if (this.rosterFilter === 'offline' && d.is_online) return false;
                        if (this.rosterFilter === 'on trip' && !d.on_trip) return false;
                        if (!q) return true;
                        return [d.name, d.phone, d.vehicle_number, d.city].some(v => (v || '').toLowerCase().includes(q));
                    });
                },

                /* ── Polling ── */
                startPolling() {
                    this.stopPolling();
                    this.pollTimer = setInterval(() => this.refresh(), 3000);
                },
                stopPolling() {
                    if (this.pollTimer) { clearInterval(this.pollTimer); this.pollTimer = null; }
                },
                togglePolling() {
                    this.polling = !this.polling;
                    if (this.polling) { this.startPolling(); this.refresh(); }
                    else { this.stopPolling(); }
                },
                async refresh() {
                    try {
                        const res = await fetch(this.feedUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        if (!res.ok) {
                            // Session expired — bounce to login instead of silently showing stale data.
                            if (res.status === 401 || res.status === 419) { window.location.reload(); return; }
                            console.warn('God\'s Eye feed returned HTTP ' + res.status);
                            this.stale = true;
                            return;
                        }
                        this.data = await res.json();
                        this.stale = false;
                        this.syncDriverMarkers();
                        this.rehydrateDetail();
                    } catch (e) {
                        // Network blip — keep the last good snapshot but flag it as stale.
                        console.warn('God\'s Eye feed poll failed:', e);
                        this.stale = true;
                    }
                },

                /* Keep the open inspector in sync with fresh data. */
                rehydrateDetail() {
                    if (!this.detail) return;
                    if (this.detail.kind === 'driver') {
                        const fresh = this.data.drivers.find(d => d.id === this.detail.data.id);
                        if (fresh) this.detail = { kind: 'driver', data: fresh };
                    } else if (this.detail.kind === 'trip') {
                        const pool = [...this.data.active_trips, ...this.data.recent_trips];
                        const fresh = pool.find(t => t.id === this.detail.data.id);
                        if (fresh) { this.detail = { kind: 'trip', data: fresh }; this.drawTrip(fresh); }
                    }
                },

                /* ── Inspector ── */
                focusDriver(d) {
                    this.selectedDriverId = d.id;
                    this.detail = { kind: 'driver', data: d };
                    if (this.mapReady && d.has_location) {
                        this.clearTripMarkers();
                        this.map.panTo({ lat: d.lat, lng: d.lng });
                        this.map.setZoom(15);
                        if (this.markers[d.id]) this.bounceMarker(this.markers[d.id]);
                    }
                },
                focusTrip(t) {
                    this.selectedDriverId = t.driver ? t.driver.id : null;
                    this.detail = { kind: 'trip', data: t };
                    this.drawTrip(t);
                },

                /* ── Google Maps ── */
                loadGoogleMaps() {
                    // SDK already present (e.g. returning to a cached page) — init now.
                    if (window.google && window.google.maps) { this.initMap(); return; }
                    // SDK script in flight from a prior mount — just wait for it.
                    // No { once: true } so a remount that missed the first event still inits.
                    if (document.getElementById('gmaps-sdk')) {
                        window.addEventListener('gmaps:ready', () => this.initMap());
                        return;
                    }
                    // First load: a single shared global callback that fans out via an event.
                    if (!window.__godsEyeMapInit) {
                        window.__godsEyeMapInit = () => window.dispatchEvent(new Event('gmaps:ready'));
                    }
                    window.addEventListener('gmaps:ready', () => this.initMap());
                    const s = document.createElement('script');
                    s.id = 'gmaps-sdk';
                    s.async = true;
                    s.defer = true;
                    s.src = 'https://maps.googleapis.com/maps/api/js?key={{ $mapKey }}&callback=__godsEyeMapInit';
                    document.head.appendChild(s);
                },
                initMap() {
                    if (this.mapReady) return; // guard against duplicate gmaps:ready events
                    const center = this.fleetCenter();
                    this.map = new google.maps.Map(document.getElementById('godsEyeMap'), {
                        center, zoom: 12, disableDefaultUI: false, streetViewControl: false,
                        mapTypeControl: false, fullscreenControl: true,
                        styles: document.documentElement.classList.contains('dark') ? this.darkMapStyle() : [],
                    });
                    this.mapReady = true;
                    this.syncDriverMarkers();
                },
                fleetCenter() {
                    const located = this.data.drivers.filter(d => d.has_location);
                    if (located.length) {
                        const lat = located.reduce((a, d) => a + d.lat, 0) / located.length;
                        const lng = located.reduce((a, d) => a + d.lng, 0) / located.length;
                        return { lat, lng };
                    }
                    return { lat: 20.5937, lng: 78.9629 }; // India fallback
                },
                syncDriverMarkers() {
                    if (!this.mapReady) return;
                    const live = {};
                    this.data.drivers.filter(d => d.is_online && d.has_location).forEach(d => {
                        live[d.id] = true;
                        const pos = { lat: d.lat, lng: d.lng };
                        if (this.markers[d.id]) {
                            this.markers[d.id].setPosition(pos);
                            this.markers[d.id].setIcon(this.driverIcon(d.on_trip));
                        } else {
                            const m = new google.maps.Marker({
                                position: pos, map: this.map, title: d.name,
                                icon: this.driverIcon(d.on_trip),
                            });
                            m.addListener('click', () => this.focusDriver(d));
                            this.markers[d.id] = m;
                        }
                    });
                    // Remove markers for drivers that went offline / lost GPS.
                    Object.keys(this.markers).forEach(id => {
                        if (!live[id]) { this.markers[id].setMap(null); delete this.markers[id]; }
                    });
                },
                driverIcon(onTrip) {
                    const color = onTrip ? '#2196f3' : '#00ab55';
                    return {
                        path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z',
                        fillColor: color, fillOpacity: 1, strokeColor: '#ffffff', strokeWeight: 2,
                        scale: 1.6, anchor: new google.maps.Point(12, 22),
                    };
                },
                drawTrip(t) {
                    if (!this.mapReady) return;
                    this.clearTripMarkers();
                    const bounds = new google.maps.LatLngBounds();
                    const add = (lat, lng, color, label) => {
                        if (lat === null || lng === null) return;
                        const pos = { lat, lng };
                        const m = new google.maps.Marker({
                            position: pos, map: this.map, title: label,
                            icon: { path: google.maps.SymbolPath.CIRCLE, scale: 7, fillColor: color, fillOpacity: 1, strokeColor: '#fff', strokeWeight: 2 },
                        });
                        this.tripMarkers.push(m);
                        bounds.extend(pos);
                    };
                    add(t.pickup_lat, t.pickup_lng, '#018DBD', 'Pickup');
                    add(t.drop_lat, t.drop_lng, '#e7515a', 'Drop-off');
                    if (t.has_live_location) add(t.live_lat, t.live_lng, '#2196f3', 'Live');
                    // Route polyline pickup → drop.
                    if (t.pickup_lat !== null && t.drop_lat !== null) {
                        const line = new google.maps.Polyline({
                            path: [{ lat: t.pickup_lat, lng: t.pickup_lng }, { lat: t.drop_lat, lng: t.drop_lng }],
                            geodesic: true, strokeColor: '#018DBD', strokeOpacity: 0.7, strokeWeight: 3, map: this.map,
                        });
                        this.tripMarkers.push(line);
                    }
                    if (!bounds.isEmpty()) {
                        this.map.fitBounds(bounds, 80);
                        if (this.tripMarkers.length === 1) this.map.setZoom(15);
                    }
                },
                clearTripMarkers() {
                    this.tripMarkers.forEach(m => m.setMap(null));
                    this.tripMarkers = [];
                },
                bounceMarker(m) {
                    if (!m.getAnimation) return;
                    m.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => m.setAnimation(null), 1400);
                },

                /* ── helpers ── */
                formatNum(n) { return Number(n || 0).toLocaleString('en-IN'); },
                statusClass(b) {
                    return {
                        completed: 'bg-success/10 text-success',
                        active:    'bg-info/10 text-info',
                        placed:    'bg-warning/10 text-warning',
                        cancelled: 'bg-danger/10 text-danger',
                    }[b] || 'bg-gray-100 text-gray-500 dark:bg-gray-800';
                },
                statusDot(b) {
                    return { completed: 'bg-success', active: 'bg-info', placed: 'bg-warning', cancelled: 'bg-danger' }[b] || 'bg-gray-400';
                },
                darkMapStyle() {
                    return [
                        { elementType: 'geometry', stylers: [{ color: '#1d2c4d' }] },
                        { elementType: 'labels.text.fill', stylers: [{ color: '#8ec3b9' }] },
                        { elementType: 'labels.text.stroke', stylers: [{ color: '#1a3646' }] },
                        { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#304a7d' }] },
                        { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0e1626' }] },
                        { featureType: 'poi', stylers: [{ visibility: 'off' }] },
                    ];
                },
            }));
        });
    </script>
</x-layout.admin>
