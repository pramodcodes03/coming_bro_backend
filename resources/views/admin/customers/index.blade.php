<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customers</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all registered customers</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.customers.export', request()->query()) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Excel
                </a>
                <a href="{{ route('admin.customers.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3] shadow-sm hover:opacity-90 transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Add New Customer
                </a>
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
        @php $hasFilters = collect(['search','name','email','mobile','ip','status','date_from','date_to'])->contains(fn($f) => request()->filled($f)); @endphp
        <div class="panel">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="space-y-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Quick search by name, email, phone, or IP..."
                           class="w-full pl-10 form-input">
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Name</label>
                        <input type="text" name="name" value="{{ request('name') }}" placeholder="Full name" class="form-input">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Mobile</label>
                        <input type="text" name="mobile" value="{{ request('mobile') }}" placeholder="Phone number" class="form-input">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Email</label>
                        <input type="text" name="email" value="{{ request('email') }}" placeholder="Email" class="form-input">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">IP Address</label>
                        <input type="text" name="ip" value="{{ request('ip') }}" placeholder="Register / last login IP" class="form-input">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Registered From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Registered To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        @if($hasFilters)
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="panel">
            <div class="overflow-x-auto">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>IP Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-center w-44">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td class="font-semibold whitespace-nowrap">{{ $customer->full_name }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td class="whitespace-nowrap">{{ $customer->country_code }}{{ $customer->phone_number }}</td>
                                <td class="whitespace-nowrap text-xs">
                                    @if($customer->last_login_ip || $customer->register_ip)
                                        <div><span class="text-gray-400">Last:</span> {{ $customer->last_login_ip ?? '-' }}</div>
                                        <div><span class="text-gray-400">Reg:</span> {{ $customer->register_ip ?? '-' }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.customers.toggle-status', $customer->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }} cursor-pointer hover:opacity-80 transition">
                                            {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#018DBD] bg-[#018DBD]/10 rounded-md hover:bg-[#018DBD]/20 transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.customers.destroy', $customer->id) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition dark:bg-red-900/20 dark:hover:bg-red-900/30">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>No customers found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="pt-5 mt-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $customers->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout.admin>
