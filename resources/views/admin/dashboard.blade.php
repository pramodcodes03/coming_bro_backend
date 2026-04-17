<x-layout.admin>
    <div class="space-y-6">

        {{-- ── Page Header ── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                    <span class="animate-wave">👋</span> Welcome back, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Here's what's happening with your platform today.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 shadow-sm dark:bg-[#0e1726]">
                    <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5 rounded-lg bg-success/10 px-3 py-2.5">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-success"></span>
                    </span>
                    <span class="text-sm font-semibold text-success">{{ number_format($onlineDrivers) }} Online</span>
                </div>
            </div>
        </div>

        {{-- ── Row 1: Primary KPI Cards ── --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Revenue --}}
            <div class="panel relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalRevenue, 0) }}</h3>
                        <div class="flex items-center gap-1.5">
                            @if($monthlyRevenueGrowth >= 0)
                                <span class="flex items-center gap-0.5 rounded-full bg-success/15 px-2 py-0.5 text-xs font-bold text-success">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 15l7-7 7 7"/></svg>
                                    {{ $monthlyRevenueGrowth }}%
                                </span>
                            @else
                                <span class="flex items-center gap-0.5 rounded-full bg-danger/15 px-2 py-0.5 text-xs font-bold text-danger">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                    {{ abs($monthlyRevenueGrowth) }}%
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">vs last month</span>
                        </div>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-secondary text-white shadow-lg shadow-primary/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="absolute -bottom-3 -right-3 h-24 w-24 rounded-full bg-primary/5"></div>
            </div>

            {{-- Total Orders --}}
            <div class="panel relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalOrders) }}</h3>
                        <div class="flex items-center gap-1.5">
                            @if($monthlyOrderGrowth >= 0)
                                <span class="flex items-center gap-0.5 rounded-full bg-success/15 px-2 py-0.5 text-xs font-bold text-success">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 15l7-7 7 7"/></svg>
                                    {{ $monthlyOrderGrowth }}%
                                </span>
                            @else
                                <span class="flex items-center gap-0.5 rounded-full bg-danger/15 px-2 py-0.5 text-xs font-bold text-danger">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                    {{ abs($monthlyOrderGrowth) }}%
                                </span>
                            @endif
                            <span class="text-xs text-gray-400">vs last month</span>
                        </div>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-secondary to-success text-white shadow-lg shadow-secondary/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                </div>
                <div class="absolute -bottom-3 -right-3 h-24 w-24 rounded-full bg-secondary/5"></div>
            </div>

            {{-- Total Customers --}}
            <div class="panel relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Customers</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</h3>
                        <div class="flex items-center gap-1.5">
                            <span class="flex items-center gap-0.5 rounded-full bg-info/15 px-2 py-0.5 text-xs font-bold text-info">
                                +{{ number_format($newCustomersThisMonth) }}
                            </span>
                            <span class="text-xs text-gray-400">this month</span>
                        </div>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-info to-primary text-white shadow-lg shadow-info/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                </div>
                <div class="absolute -bottom-3 -right-3 h-24 w-24 rounded-full bg-info/5"></div>
            </div>

            {{-- Total Drivers --}}
            <div class="panel relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Drivers</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDrivers) }}</h3>
                        <div class="flex items-center gap-1.5">
                            <span class="flex items-center gap-0.5 rounded-full bg-warning/15 px-2 py-0.5 text-xs font-bold text-warning">
                                +{{ number_format($newDriversThisMonth) }}
                            </span>
                            <span class="text-xs text-gray-400">this month</span>
                        </div>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-warning to-danger text-white shadow-lg shadow-warning/30">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75M2.25 14.25h1.5m13.5 0h1.5m-16.5 0V6.375c0-.621.504-1.125 1.125-1.125h3.026a2.999 2.999 0 012.25 1.016l.272.341a3 3 0 002.25 1.016h5.577c.621 0 1.125.504 1.125 1.125V14.25"/></svg>
                    </div>
                </div>
                <div class="absolute -bottom-3 -right-3 h-24 w-24 rounded-full bg-warning/5"></div>
            </div>
        </div>

        {{-- ── Row 2: Today's Snapshot + Driver Overview ── --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

            {{-- Today's Performance --}}
            <div class="panel lg:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Today's Performance</h2>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">LIVE</span>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    {{-- Today Orders --}}
                    <div class="rounded-xl border border-gray-200/60 bg-gray-50/50 p-4 dark:border-gray-700/40 dark:bg-white/[0.03]">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10">
                                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Orders</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayOrders) }}</p>
                        <div class="mt-1 flex items-center gap-1">
                            @if($orderGrowth >= 0)
                                <svg class="h-3 w-3 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 15l7-7 7 7"/></svg>
                                <span class="text-xs font-semibold text-success">{{ $orderGrowth }}%</span>
                            @else
                                <svg class="h-3 w-3 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                <span class="text-xs font-semibold text-danger">{{ abs($orderGrowth) }}%</span>
                            @endif
                        </div>
                    </div>

                    {{-- Today Revenue --}}
                    <div class="rounded-xl border border-gray-200/60 bg-gray-50/50 p-4 dark:border-gray-700/40 dark:bg-white/[0.03]">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-success/10">
                                <svg class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Revenue</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($todayRevenue, 0) }}</p>
                        <div class="mt-1 flex items-center gap-1">
                            @if($revenueGrowth >= 0)
                                <svg class="h-3 w-3 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 15l7-7 7 7"/></svg>
                                <span class="text-xs font-semibold text-success">{{ $revenueGrowth }}%</span>
                            @else
                                <svg class="h-3 w-3 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                <span class="text-xs font-semibold text-danger">{{ abs($revenueGrowth) }}%</span>
                            @endif
                        </div>
                    </div>

                    {{-- Completed Today --}}
                    <div class="rounded-xl border border-gray-200/60 bg-gray-50/50 p-4 dark:border-gray-700/40 dark:bg-white/[0.03]">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-secondary/10">
                                <svg class="h-5 w-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Completed</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayCompleted) }}</p>
                        <p class="mt-1 text-xs text-gray-400">rides today</p>
                    </div>

                    {{-- Active Now --}}
                    <div class="rounded-xl border border-gray-200/60 bg-gray-50/50 p-4 dark:border-gray-700/40 dark:bg-white/[0.03]">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-warning/10">
                                <svg class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Active</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($activeOrders) }}</p>
                        <p class="mt-1 text-xs text-gray-400">in progress</p>
                    </div>
                </div>
            </div>

            {{-- Driver Overview --}}
            <div class="panel">
                <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">Driver Overview</h2>
                <div class="space-y-4">
                    {{-- Online --}}
                    <div class="flex items-center justify-between rounded-lg bg-success/5 p-3 dark:bg-success/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-success/20">
                                <span class="relative flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-success"></span>
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Online Now</p>
                                <p class="text-xs text-gray-500">Active on map</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-success">{{ number_format($onlineDrivers) }}</span>
                    </div>
                    {{-- Verified --}}
                    <div class="flex items-center justify-between rounded-lg bg-info/5 p-3 dark:bg-info/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-info/20">
                                <svg class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Verified</p>
                                <p class="text-xs text-gray-500">Documents approved</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-info">{{ number_format($verifiedDrivers) }}</span>
                    </div>
                    {{-- Subscribed --}}
                    <div class="flex items-center justify-between rounded-lg bg-warning/5 p-3 dark:bg-warning/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-warning/20">
                                <svg class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Subscribed</p>
                                <p class="text-xs text-gray-500">Active plans</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-warning">{{ number_format($subscribedDrivers) }}</span>
                    </div>
                    {{-- Offline --}}
                    <div class="flex items-center justify-between rounded-lg bg-gray-100/80 p-3 dark:bg-gray-800/40">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Offline</p>
                                <p class="text-xs text-gray-500">Not available</p>
                            </div>
                        </div>
                        <span class="text-xl font-bold text-gray-600 dark:text-gray-400">{{ number_format($totalDrivers - $onlineDrivers) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Row 3: Revenue Chart + Order Status Donut ── --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            {{-- Revenue Trend Chart --}}
            <div class="panel lg:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Revenue Overview</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Monthly revenue for the last 6 months</p>
                    </div>
                    <div class="rounded-lg bg-primary/10 px-3 py-1.5">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">This Month</p>
                        <p class="text-lg font-bold text-primary">₹{{ number_format($thisMonthRevenue, 0) }}</p>
                    </div>
                </div>
                <div id="revenueChart"></div>
            </div>

            {{-- Order Status Distribution --}}
            <div class="panel">
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Order Status</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">All-time distribution</p>
                </div>
                <div id="orderStatusChart" class="flex items-center justify-center"></div>
                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-success"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completed</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($completedOrders) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-primary"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Active</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($activeOrders) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-warning"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Placed</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($placedOrders) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-danger"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cancelled</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($cancelledOrders) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Row 4: Order Trend Bar Chart + Performance Metrics ── --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            {{-- Weekly Order Trend --}}
            <div class="panel lg:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Order Trends</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">City vs Intercity orders - last 7 days</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                            <span class="text-xs text-gray-500">City</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-secondary"></span>
                            <span class="text-xs text-gray-500">Intercity</span>
                        </div>
                    </div>
                </div>
                <div id="orderTrendChart"></div>
            </div>

            {{-- Performance Metrics --}}
            <div class="panel">
                <h2 class="mb-5 text-lg font-bold text-gray-900 dark:text-white">Performance</h2>
                <div class="space-y-5">
                    {{-- Completion Rate --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Completion Rate</span>
                            <span class="text-sm font-bold text-success">{{ $completionRate }}%</span>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-2.5 rounded-full bg-gradient-to-r from-success/80 to-success transition-all duration-500" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                    {{-- Cancellation Rate --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Cancellation Rate</span>
                            <span class="text-sm font-bold text-danger">{{ $cancellationRate }}%</span>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-2.5 rounded-full bg-gradient-to-r from-danger/80 to-danger transition-all duration-500" style="width: {{ $cancellationRate }}%"></div>
                        </div>
                    </div>
                    {{-- Avg Rating --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Driver Rating</span>
                            <span class="text-sm font-bold text-warning">{{ number_format($averageRating, 1) }} / 5</span>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-2.5 rounded-full bg-gradient-to-r from-warning/80 to-warning transition-all duration-500" style="width: {{ ($averageRating / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="!mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-primary/5 p-3 text-center dark:bg-primary/10">
                            <p class="text-xl font-bold text-primary">{{ number_format($totalCityOrders) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">City Rides</p>
                        </div>
                        <div class="rounded-xl bg-secondary/5 p-3 text-center dark:bg-secondary/10">
                            <p class="text-xl font-bold text-secondary">{{ number_format($totalIntercityOrders) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Intercity Rides</p>
                        </div>
                        <div class="rounded-xl bg-danger/5 p-3 text-center dark:bg-danger/10">
                            <p class="text-xl font-bold text-danger">{{ number_format($pendingPayouts) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pending Payouts</p>
                        </div>
                        <div class="rounded-xl bg-warning/5 p-3 text-center dark:bg-warning/10">
                            <p class="text-xl font-bold text-warning">{{ number_format($totalReviews) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Row 5: Monthly Trend Table + Top Drivers ── --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

            {{-- Monthly Breakdown Table --}}
            <div class="panel lg:col-span-2">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Monthly Breakdown</h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Last 6 months</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Intercity</th>
                                <th class="text-center">Total</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyTrend as $month)
                                <tr>
                                    <td class="font-semibold text-gray-900 dark:text-white">{{ $month['label'] }}</td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center justify-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-bold text-primary">{{ number_format($month['city']) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-flex items-center justify-center rounded-full bg-secondary/10 px-2.5 py-0.5 text-xs font-bold text-secondary">{{ number_format($month['intercity']) }}</span>
                                    </td>
                                    <td class="text-center font-semibold">{{ number_format($month['total']) }}</td>
                                    <td class="text-right font-bold text-success">₹{{ number_format($month['revenue'], 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Top Drivers --}}
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Top Drivers</h2>
                    <a href="{{ route('admin.drivers.index') }}" class="text-xs font-semibold text-primary hover:underline">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($topDrivers as $index => $driver)
                        <div class="flex items-center gap-3">
                            {{-- Rank Badge --}}
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full font-bold
                                {{ $index === 0 ? 'bg-warning/20 text-warning' : ($index === 1 ? 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300' : ($index === 2 ? 'bg-orange-100 text-orange-500 dark:bg-orange-500/20' : 'bg-gray-100 text-gray-500 dark:bg-gray-800')) }}">
                                {{ $index + 1 }}
                            </div>
                            {{-- Avatar --}}
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary to-secondary text-sm font-bold text-white">
                                {{ strtoupper(substr($driver->full_name ?? 'D', 0, 1)) }}
                            </div>
                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $driver->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($driver->total_completed) }} rides</p>
                            </div>
                            {{-- Earnings --}}
                            <div class="text-right">
                                <p class="text-sm font-bold text-success">₹{{ number_format($driver->total_earnings, 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500">
                            <svg class="mx-auto mb-2 h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            No driver data yet
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── Row 6: Recent Orders Table ── --}}
        <div class="panel">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Orders</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Latest city ride orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Driver</th>
                            <th>Route</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="font-bold text-primary">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-info/10 text-xs font-bold text-info">
                                            {{ strtoupper(substr($order->customer->full_name ?? 'C', 0, 1)) }}
                                        </div>
                                        <span class="whitespace-nowrap font-medium">{{ $order->customer->full_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @if($order->driver)
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-success/10 text-xs font-bold text-success">
                                                {{ strtoupper(substr($order->driver->full_name, 0, 1)) }}
                                            </div>
                                            <span class="whitespace-nowrap font-medium">{{ $order->driver->full_name }}</span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500 dark:bg-gray-800">Unassigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="max-w-[200px]">
                                        <p class="truncate text-xs text-gray-500" title="{{ $order->source_location_name }}">{{ $order->source_location_name ?? '-' }}</p>
                                        <p class="truncate text-xs font-medium text-gray-700 dark:text-gray-300" title="{{ $order->destination_location_name }}">{{ $order->destination_location_name ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusConfig = match(strtolower($order->status ?? '')) {
                                            'completed' => ['bg' => 'bg-success/10', 'text' => 'text-success', 'dot' => 'bg-success'],
                                            'cancelled' => ['bg' => 'bg-danger/10', 'text' => 'text-danger', 'dot' => 'bg-danger'],
                                            'placed' => ['bg' => 'bg-warning/10', 'text' => 'text-warning', 'dot' => 'bg-warning'],
                                            'in_progress', 'ongoing' => ['bg' => 'bg-info/10', 'text' => 'text-info', 'dot' => 'bg-info'],
                                            default => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'dot' => 'bg-primary'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 rounded-full {{ $statusConfig['bg'] }} px-3 py-1 text-xs font-semibold {{ $statusConfig['text'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                        {{ ucfirst(str_replace('_', ' ', $order->status ?? 'Unknown')) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="font-bold text-gray-900 dark:text-white">₹{{ number_format($order->final_rate ?? 0, 0) }}</span>
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_date?->format('d M, h:i A') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center">
                                    <svg class="mx-auto mb-2 h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                                    <p class="text-sm text-gray-500">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Row 7: Quick Financial Summary ── --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="panel border-l-4 border-l-primary">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary/10">
                        <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">This Month Revenue</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">₹{{ number_format($thisMonthRevenue, 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="panel border-l-4 border-l-danger">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-danger/10">
                        <svg class="h-6 w-6 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending Payouts</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">₹{{ number_format($pendingPayoutAmount, 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="panel border-l-4 border-l-warning">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-warning/10">
                        <svg class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Wallet Balance</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">₹{{ number_format($totalWalletBalance, 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="panel border-l-4 border-l-success">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-success/10">
                        <svg class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">This Month Orders</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($thisMonthOrders) }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── ApexCharts Scripts ── --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.body.classList.contains('dark') || document.querySelector('html').classList.contains('dark');
            const fontFamily = 'Nunito, sans-serif';
            const gridColor = isDark ? '#1b2e4b' : '#e0e6ed';
            const labelColor = isDark ? '#888ea8' : '#6b7280';

            // ── Revenue Area Chart ──
            new ApexCharts(document.querySelector('#revenueChart'), {
                series: [{
                    name: 'Revenue',
                    data: @json($monthlyTrend->pluck('revenue'))
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    fontFamily: fontFamily,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                },
                colors: ['#018DBD'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 95, 100]
                    }
                },
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: @json($monthlyTrend->pluck('label')),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: labelColor, fontSize: '12px' } },
                },
                yaxis: {
                    labels: {
                        style: { colors: labelColor, fontSize: '12px' },
                        formatter: function(val) {
                            if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                            if (val >= 1000) return '₹' + (val / 1000).toFixed(1) + 'K';
                            return '₹' + val;
                        }
                    },
                },
                grid: { borderColor: gridColor, strokeDashArray: 4, padding: { left: 10 } },
                tooltip: {
                    y: { formatter: function(val) { return '₹' + val.toLocaleString('en-IN'); } }
                },
            }).render();

            // ── Order Status Donut Chart ──
            new ApexCharts(document.querySelector('#orderStatusChart'), {
                series: [
                    {{ $orderStatusDist['completed'] }},
                    {{ $orderStatusDist['active'] }},
                    {{ $orderStatusDist['placed'] }},
                    {{ $orderStatusDist['cancelled'] }}
                ],
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: fontFamily,
                },
                labels: ['Completed', 'Active', 'Placed', 'Cancelled'],
                colors: ['#00ab55', '#018DBD', '#e2a03f', '#e7515a'],
                stroke: { width: 0 },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '14px',
                                    fontWeight: 600,
                                    color: labelColor,
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString();
                                    }
                                },
                                value: {
                                    fontSize: '22px',
                                    fontWeight: 700,
                                    color: isDark ? '#e0e6ed' : '#0e1726',
                                }
                            }
                        }
                    }
                },
                legend: { show: false },
                dataLabels: { enabled: false },
            }).render();

            // ── Daily Order Trend Bar Chart ──
            new ApexCharts(document.querySelector('#orderTrendChart'), {
                series: [
                    { name: 'City', data: @json($dailyTrend->pluck('city')) },
                    { name: 'Intercity', data: @json($dailyTrend->pluck('intercity')) },
                ],
                chart: {
                    type: 'bar',
                    height: 280,
                    fontFamily: fontFamily,
                    stacked: true,
                    toolbar: { show: false },
                },
                colors: ['#018DBD', '#13C3C3'],
                plotOptions: {
                    bar: { columnWidth: '45%', borderRadius: 6, borderRadiusApplication: 'end', borderRadiusWhenStacked: 'last' }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: @json($dailyTrend->pluck('label')),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: labelColor, fontSize: '12px' } },
                },
                yaxis: {
                    labels: { style: { colors: labelColor, fontSize: '12px' } },
                },
                grid: { borderColor: gridColor, strokeDashArray: 4, padding: { left: 10 } },
                legend: { show: false },
                tooltip: {
                    x: {
                        formatter: function(val, opts) {
                            var dates = @json($dailyTrend->pluck('date'));
                            return dates[opts.dataPointIndex] || val;
                        }
                    }
                },
            }).render();
        });
    </script>
</x-layout.admin>
