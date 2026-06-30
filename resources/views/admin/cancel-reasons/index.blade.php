<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold">Cancel Reasons</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Reasons shown on the customer &amp; driver cancel-ride sheets</p>
        </div>
        <a href="{{ route('admin.cancel-reasons.create') }}"
            class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">+ Add Reason</a>
    </div>

    @if(session('success'))
        <div class="flex items-center rounded bg-success/20 p-3.5 text-success"><span>{{ session('success') }}</span></div>
    @endif

    <div class="panel">
        <form method="GET" action="{{ route('admin.cancel-reasons.index') }}" class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Reason text" class="form-input">
            </div>
            <div>
                <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Applies To</label>
                <select name="applies_to" class="form-select">
                    <option value="">All</option>
                    <option value="customer" @selected(request('applies_to') === 'customer')>Customer</option>
                    <option value="driver" @selected(request('applies_to') === 'driver')>Driver</option>
                    <option value="both" @selected(request('applies_to') === 'both')>Both</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if(request('search') || request('applies_to'))
                    <a href="{{ route('admin.cancel-reasons.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Reason</th>
                        <th>Applies To</th>
                        <th>Sort</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reasons as $reason)
                        <tr>
                            <td>{{ $reason->id }}</td>
                            <td class="font-semibold">{{ $reason->reason }}</td>
                            <td>
                                <span class="badge {{ $reason->applies_to === 'driver' ? 'bg-info' : ($reason->applies_to === 'customer' ? 'bg-primary' : 'bg-secondary') }}">
                                    {{ ucfirst($reason->applies_to) }}
                                </span>
                            </td>
                            <td>{{ $reason->sort_order }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.cancel-reasons.toggle-status', $reason->id) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="badge {{ $reason->is_active ? 'bg-success' : 'bg-danger' }} cursor-pointer hover:opacity-80">
                                        {{ $reason->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.cancel-reasons.edit', $reason->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#018DBD] bg-[#018DBD]/10 rounded-md hover:bg-[#018DBD]/20 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.cancel-reasons.destroy', $reason->id) }}" class="inline"
                                          onsubmit="return confirm('Delete this cancel reason?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition dark:bg-red-900/20">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-gray-500">No cancel reasons found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reasons->hasPages())
            <div class="mt-4">{{ $reasons->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
