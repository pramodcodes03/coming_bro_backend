<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">User Wallet Transactions</h2>
        <a href="{{ route('admin.wallet.user.export', request()->query()) }}"
           class="inline-flex items-center gap-2 btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Excel
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center rounded bg-success/20 p-3.5 text-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @php $hasFilters = collect(['search','date_from','date_to'])->contains(fn($f) => request()->filled($f)); @endphp
    <div class="panel">
        <form method="GET" action="{{ route('admin.wallet.user') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="User name, mobile, or transaction ID" class="form-input" />
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" />
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" />
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Apply</button>
                @if($hasFilters)
                    <a href="{{ route('admin.wallet.user') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="panel">
        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Mobile</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">GST</th>
                        <th>Payment Type</th>
                        <th>Note</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $transaction->user_name ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap">{{ $transaction->user_mobile ?? '-' }}</td>
                            <td class="text-right">
                                <span class="font-semibold {{ $transaction->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->total_amount ?? $transaction->amount, 2) }}
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap text-warning">
                                {{ $transaction->gst_amount !== null ? '₹' . number_format($transaction->gst_amount, 2) : '-' }}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $transaction->payment_type ?? '-')) }}</span>
                            </td>
                            <td class="max-w-xs truncate">{{ $transaction->note ?? '-' }}</td>
                            <td class="whitespace-nowrap">{{ $transaction->created_at ? $transaction->created_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No user wallet transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="mt-4">{{ $transactions->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
