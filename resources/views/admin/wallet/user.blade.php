<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">User Wallet Transactions</h2>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.wallet.user') }}" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..."
                    class="form-input w-64 pr-10" />
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center rounded bg-success/20 p-3.5 text-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="panel">
        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Payment Type</th>
                        <th>Note</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $transaction->user->full_name ?? 'N/A' }}</td>
                            <td>
                                <span class="font-semibold {{ $transaction->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->amount >= 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $transaction->payment_type ?? $transaction->type ?? '-')) }}</span>
                            </td>
                            <td class="max-w-xs truncate">{{ $transaction->note ?? '-' }}</td>
                            <td class="whitespace-nowrap">{{ $transaction->created_at ? $transaction->created_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No user wallet transactions found.</td>
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
