<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->id }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">City ride order details</p>
                </div>
            </div>
            <div>
                @php
                    $statusClasses = [
                        'placed' => 'bg-warning',
                        'pending' => 'bg-warning',
                        'accepted' => 'bg-primary',
                        'arriving' => 'bg-primary',
                        'arrived' => 'bg-primary',
                        'in_progress' => 'bg-primary',
                        'ongoing' => 'bg-primary',
                        'completed' => 'bg-success',
                        'cancelled' => 'bg-danger',
                    ];
                    $badgeClass = $statusClasses[$order->status] ?? 'bg-warning';
                @endphp
                <span class="badge {{ $badgeClass }} text-sm px-4 py-1.5">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Panel 1: Order Information -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Order Information</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Order ID</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">#{{ $order->id }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                        <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Payment Type</span>
                        <span class="text-sm font-medium text-gray-900 capitalize dark:text-white">{{ $order->payment_type ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Payment Status</span>
                        <span class="badge {{ $order->payment_status ? 'bg-success' : 'bg-warning' }}">
                            {{ $order->payment_status ? 'Paid' : 'Unpaid' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Created</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_date ? $order->created_date->format('d M Y, h:i A') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Updated</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->update_date ? $order->update_date->format('d M Y, h:i A') : '-' }}</span>
                    </div>
                </div>

                <!-- Status Update Form -->
                @if(!in_array($order->status, ['completed', 'cancelled']))
                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="flex items-end gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex-1">
                                <label class="block mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400">Update Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['placed', 'accepted', 'arriving', 'arrived', 'in_progress', 'ongoing', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Panel 2: Route Information -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Route Details</h3>
                </div>

                <div class="space-y-4">
                    <!-- Source -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-8 h-8 text-white bg-green-500 rounded-full shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="4"/></svg>
                            </div>
                            <div class="flex-1 w-0.5 bg-gray-200 dark:bg-gray-700 my-1"></div>
                        </div>
                        <div class="flex-1 pb-4">
                            <p class="text-xs font-semibold text-green-600 uppercase dark:text-green-400">Pickup</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->source_location_name ?? 'Not specified' }}</p>
                            @if($order->source_latitude && $order->source_longitude)
                                <p class="mt-1 text-xs text-gray-400">{{ $order->source_latitude }}, {{ $order->source_longitude }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Destination -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-8 h-8 text-white bg-red-500 rounded-full shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-red-600 uppercase dark:text-red-400">Drop-off</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->destination_location_name ?? 'Not specified' }}</p>
                            @if($order->destination_latitude && $order->destination_longitude)
                                <p class="mt-1 text-xs text-gray-400">{{ $order->destination_latitude }}, {{ $order->destination_longitude }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->distance || $order->duration)
                    <div class="grid grid-cols-2 gap-3 pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Distance</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->distance ?? '-' }} {{ $order->distance_type ?? 'km' }}</p>
                        </div>
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Duration</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->duration ?? '-' }} min</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel 3: Customer & Driver -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Customer & Driver</h3>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Customer -->
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-3 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">Customer</p>
                        @if($order->customer)
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-blue-500 to-blue-600">
                                    {{ strtoupper(substr($order->customer->full_name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->customer->full_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $order->customer->id }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                @if($order->customer->phone_number)
                                    <p>{{ $order->customer->country_code }}{{ $order->customer->phone_number }}</p>
                                @endif
                                @if($order->customer->email)
                                    <p>{{ $order->customer->email }}</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer not found</p>
                        @endif
                    </div>

                    <!-- Driver -->
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-3 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">Driver</p>
                        @if($order->driver)
                            <div class="flex items-center gap-3 mb-3">
                                @if($order->driver->profile_pic)
                                    <img src="{{ $order->driver->profile_pic }}" alt="{{ $order->driver->full_name }}" class="object-cover w-10 h-10 rounded-full">
                                @else
                                    <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full bg-gradient-to-r from-green-500 to-green-600">
                                        {{ strtoupper(substr($order->driver->full_name ?? 'D', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->driver->full_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $order->driver->id }}</p>
                                </div>
                            </div>
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                @if($order->driver->phone_number)
                                    <p>{{ $order->driver->country_code }}{{ $order->driver->phone_number }}</p>
                                @endif
                                @if($order->driver->vehicle_number)
                                    <p>Vehicle: {{ $order->driver->vehicle_number }}</p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No driver assigned</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel 4: Pricing Breakdown -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pricing Breakdown</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Offer Rate</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->offer_rate ? '₹' . number_format($order->offer_rate, 2) : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Final Rate</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $order->final_rate ? '₹' . number_format($order->final_rate, 2) : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Distance</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->distance ?? '-' }} {{ $order->distance_type ?? 'km' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Duration</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->duration ?? '-' }} min</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Ride Time Fare/min</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->ride_time_fare_per_minute ? '₹' . number_format($order->ride_time_fare_per_minute, 2) : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Ride Time</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->total_ride_time ?? '-' }} min</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Holding Charges</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->total_holding_charges ? '₹' . number_format($order->total_holding_charges, 2) : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">AC/Non-AC Charges</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->ac_non_ac_charges ? '₹' . number_format($order->ac_non_ac_charges, 2) : '-' }}</span>
                    </div>

                    @if($order->tax_list && is_array($order->tax_list))
                        @foreach($order->tax_list as $tax)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Tax: {{ is_array($tax) ? ($tax['name'] ?? $tax['title'] ?? 'Tax') : 'Tax' }}
                                </span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if(is_array($tax))
                                        {{ isset($tax['amount']) ? '₹' . number_format($tax['amount'], 2) : (isset($tax['rate']) ? $tax['rate'] . '%' : '-') }}
                                    @else
                                        {{ $tax }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    @endif

                    @if($order->admin_commission && is_array($order->admin_commission))
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Admin Commission</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                @if(isset($order->admin_commission['amount']))
                                    ₹{{ number_format($order->admin_commission['amount'], 2) }}
                                @elseif(isset($order->admin_commission['value']))
                                    ₹{{ number_format($order->admin_commission['value'], 2) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($order->coupon && is_array($order->coupon))
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Coupon Applied</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                {{ $order->coupon['code'] ?? 'Yes' }}
                                @if(isset($order->coupon['amount']))
                                    - ₹{{ number_format($order->coupon['amount'], 2) }}
                                @endif
                            </span>
                        </div>
                    @endif

                    <!-- Total -->
                    <div class="flex items-center justify-between p-3 mt-2 rounded-lg bg-gradient-to-r from-[#018DBD]/10 to-[#13C3C3]/10">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Total Amount</span>
                        <span class="text-lg font-bold text-[#018DBD]">
                            {{ $order->final_rate ? '₹' . number_format($order->final_rate, 2) : ($order->offer_rate ? '₹' . number_format($order->offer_rate, 2) : '-') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.admin>
