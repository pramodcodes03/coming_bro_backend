<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Welcome back! Here's your platform overview.</p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
            </div>
        </div>

        <!-- Statistics Section -->
        <div>
            <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-200">Platform Overview</h2>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Customers Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-[#018DBD] to-[#0fa8a8]">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-blue-100">Total Customers</p>
                                <h3 class="text-4xl font-bold">{{ number_format($totalCustomers ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="2" />
                                    <path opacity="0.5" d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" fill="currentColor" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-blue-100">Registered customers</span>
                        </div>
                    </div>
                </div>

                <!-- Total Drivers Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-green-500 via-green-600 to-green-700">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-green-100">Total Drivers</p>
                                <h3 class="text-4xl font-bold">{{ number_format($totalDrivers ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 10H16M8 14H11M7 18V20.3355C7 20.8684 7 21.1348 7.10923 21.2716C7.21846 21.4083 7.40845 21.4619 7.78843 21.5691L8.32 21.7055C8.88 21.8485 9.16 21.92 9.46 21.9514C10.2798 22.0313 11.1071 21.9107 11.868 21.6011C12.1378 21.4885 12.3851 21.3397 12.8797 21.042L13.0261 20.9551C13.8309 20.4999 14.2333 20.2722 14.6567 20.1609C15.2666 20.0025 15.9046 20.0065 16.5126 20.1729C16.9353 20.2869 17.3366 20.5175 18.1393 20.9787L18.2794 21.0602C19.0 21.4811 19.3603 21.6916 19.6801 21.7058C20 21.7201 20.3129 21.6065 20.5481 21.3929C20.7833 21.1792 20.9 20.8486 21 20.1875V13.4286C21 10.4069 21 8.89607 20.2272 7.72688C19.9816 7.3456 19.6736 7.00819 19.3149 6.73005C18.2504 6 16.8345 6 14.0027 6H9.99729C7.16549 6 5.74959 6 4.68498 6.73005C4.32635 7.00819 4.01841 7.3456 3.77278 7.72688C3 8.89607 3 10.4069 3 13.4286V14.5714C3 17.5931 3 19.1039 3.77278 20.2731C4.01841 20.6544 4.32635 20.9918 4.68498 21.27C5.40664 21.8041 6.33466 21.9572 7.99903 21.9933" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <circle cx="12" cy="4" r="2" fill="currentColor" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-green-100">Active delivery partners</span>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-[#13C3C3] to-[#018DBD]">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-cyan-100">Total Orders</p>
                                <h3 class="text-4xl font-bold">{{ number_format($totalOrders ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z" fill="currentColor" />
                                    <path d="M8.25 10.5C8.25 10.0858 8.58579 9.75 9 9.75H15C15.4142 9.75 15.75 10.0858 15.75 10.5C15.75 10.9142 15.4142 11.25 15 11.25H9C8.58579 11.25 8.25 10.9142 8.25 10.5Z" fill="currentColor" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-cyan-100">All time deliveries</span>
                        </div>
                    </div>
                </div>

                <!-- Active Orders Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-purple-100">Active Orders</p>
                                <h3 class="text-4xl font-bold">{{ number_format($activeOrders ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                    <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2 py-1 mr-2 text-xs font-bold rounded bg-white/20">LIVE</span>
                            <span class="text-purple-100">In progress deliveries</span>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-cyan-500 via-cyan-600 to-cyan-700">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-cyan-100">Completed</p>
                                <h3 class="text-4xl font-bold">{{ number_format($completedOrders ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                    <path d="M8 12L11 15L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-cyan-100">Successfully delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Orders Card -->
                <div class="relative overflow-hidden text-white panel bg-gradient-to-br from-red-500 via-red-600 to-red-700">
                    <div class="absolute top-0 right-0 w-48 h-48 -mt-4 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="mb-1 text-sm font-medium text-red-100">Cancelled</p>
                                <h3 class="text-4xl font-bold">{{ number_format($cancelledOrders ?? 0) }}</h3>
                            </div>
                            <div class="flex items-center justify-center shadow-lg w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl">
                                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                                    <path d="M15 9L9 15M9 9L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-red-100">Cancelled deliveries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div>
            <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-200">Recent Orders</h2>
            <div class="panel">
                <div class="overflow-x-auto">
                    <table class="table-hover">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Customer</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td class="font-semibold">#{{ $order->id }}</td>
                                    <td class="whitespace-nowrap">{{ $order->customer->full_name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap">{{ $order->driver->full_name ?? 'Unassigned' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match(strtolower($order->status ?? '')) {
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-primary',
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($order->status ?? 'Unknown') }}</span>
                                    </td>
                                    <td class="whitespace-nowrap">{{ $order->created_date?->format('d M Y, h:i A') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>
