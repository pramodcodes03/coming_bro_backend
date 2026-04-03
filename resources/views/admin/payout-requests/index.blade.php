<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">Payout Requests</h2>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.payout-requests.index') }}" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search requests..."
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
                        <th>Driver Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th>Admin Note</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                            <td class="font-semibold whitespace-nowrap">{{ $request->driver->full_name ?? 'N/A' }}</td>
                            <td class="font-semibold">{{ number_format($request->amount, 2) }}</td>
                            <td>
                                @php
                                    $statusClass = match($request->status) {
                                        'approved', 'completed' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'pending' => 'bg-warning',
                                        default => 'bg-info',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td class="whitespace-nowrap">{{ $request->payment_date ? \Carbon\Carbon::parse($request->payment_date)->format('d M Y') : '-' }}</td>
                            <td class="max-w-xs truncate">{{ $request->admin_note ?? '-' }}</td>
                            <td class="text-center">
                                @if($request->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.payout-requests.update-status', $request->id) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved" />
                                            <button type="submit" class="badge bg-success cursor-pointer hover:opacity-80 transition"
                                                    onclick="return confirm('Approve this payout request?')">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.payout-requests.update-status', $request->id) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected" />
                                            <button type="submit" class="badge bg-danger cursor-pointer hover:opacity-80 transition"
                                                    onclick="return confirm('Reject this payout request?')">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No payout requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="mt-4">{{ $requests->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
