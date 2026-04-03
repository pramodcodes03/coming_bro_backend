<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">City Orders</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all city ride orders</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filters -->
        <div class="panel">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order ID..."
                           class="w-full pl-10 form-input">
                </div>
                <select name="status" class="form-select sm:w-48">
                    <option value="">All Statuses</option>
                    @foreach(['placed', 'accepted', 'arriving', 'arrived', 'in_progress', 'ongoing', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="panel">
            <div class="overflow-x-auto">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th class="w-20">#ID</th>
                            <th>Customer</th>
                            <th>Driver</th>
                            <th class="text-center">Status</th>
                            <th>Payment Type</th>
                            <th class="text-right">Amount</th>
                            <th>Date</th>
                            <th class="text-center w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="font-semibold">#{{ $order->id }}</td>
                                <td class="whitespace-nowrap">{{ $order->customer->full_name ?? '-' }}</td>
                                <td class="whitespace-nowrap">{{ $order->driver->full_name ?? 'Unassigned' }}</td>
                                <td class="text-center">
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
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="capitalize">{{ $order->payment_type ?? '-' }}</td>
                                <td class="font-semibold text-right whitespace-nowrap">
                                    @if($order->final_rate)
                                        ₹{{ number_format($order->final_rate, 2) }}
                                    @elseif($order->offer_rate)
                                        ₹{{ number_format($order->offer_rate, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ $order->created_date ? $order->created_date->format('d M Y, h:i A') : '-' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#018DBD] bg-[#018DBD]/10 rounded-md hover:bg-[#018DBD]/20 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <span>No orders found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="pt-5 mt-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout.admin>
