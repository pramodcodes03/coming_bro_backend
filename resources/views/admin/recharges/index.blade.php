<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold">Recharges &amp; GST</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Per-recharge GST breakdown and downloadable payment register</p>
        </div>
        <a href="{{ route('admin.recharges.export', request()->query()) }}"
           class="inline-flex items-center gap-2 btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </a>
    </div>

    @php $hasFilters = collect(['search','user_type','payment_type','date_from','date_to'])->contains(fn($f) => request()->filled($f)); @endphp

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="panel">
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Recharges</p>
            <p class="mt-1 text-2xl font-bold">{{ number_format($summary->cnt ?? 0) }}</p>
        </div>
        <div class="panel">
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Base Amount</p>
            <p class="mt-1 text-2xl font-bold">&#8377;{{ number_format($summary->total_base ?? 0, 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">GST Collected</p>
            <p class="mt-1 text-2xl font-bold text-warning">&#8377;{{ number_format($summary->total_gst ?? 0, 2) }}</p>
        </div>
        <div class="panel">
            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Total Collected</p>
            <p class="mt-1 text-2xl font-bold text-success">&#8377;{{ number_format($summary->total_collected ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="panel">
        <form method="GET" action="{{ route('admin.recharges.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div class="lg:col-span-2">
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, mobile, or transaction ID" class="form-input">
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">User Type</label>
                <select name="user_type" class="form-select">
                    <option value="">All</option>
                    <option value="driver" @selected(request('user_type') === 'driver')>Driver</option>
                    <option value="customer" @selected(request('user_type') === 'customer')>Customer</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Apply</button>
                @if($hasFilters)
                    <a href="{{ route('admin.recharges.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Mobile</th>
                        <th>Plan</th>
                        <th class="text-right">Base</th>
                        <th class="text-right">GST %</th>
                        <th class="text-right">GST</th>
                        <th class="text-right">Total</th>
                        <th>Payment</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recharges as $r)
                        @php
                            $total = $r->total_amount ?? $r->amount;
                            $base = $r->base_amount ?? $r->amount;
                        @endphp
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $r->user_name ?? 'N/A' }}</td>
                            <td><span class="badge {{ $r->user_type === 'driver' ? 'bg-info' : 'bg-primary' }}">{{ ucfirst($r->user_type) }}</span></td>
                            <td class="whitespace-nowrap">{{ $r->user_mobile ?? '-' }}</td>
                            <td class="whitespace-nowrap">{{ $r->plan_label ?? '-' }}</td>
                            <td class="text-right whitespace-nowrap">&#8377;{{ number_format((float) $base, 2) }}</td>
                            <td class="text-right">{{ number_format((float) ($r->gst_percent ?? 0), 2) }}%</td>
                            <td class="text-right whitespace-nowrap text-warning">&#8377;{{ number_format((float) ($r->gst_amount ?? 0), 2) }}</td>
                            <td class="text-right font-bold whitespace-nowrap text-success">&#8377;{{ number_format((float) $total, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $r->payment_type ?? '-')) }}</span></td>
                            <td class="font-mono text-xs">{{ $r->transaction_id ?? '-' }}</td>
                            <td class="whitespace-nowrap">{{ ($r->created_date ?? $r->created_at) ? ($r->created_date ?? $r->created_at)->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-6 text-center text-gray-500">No recharges found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recharges->hasPages())
            <div class="mt-4">{{ $recharges->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
