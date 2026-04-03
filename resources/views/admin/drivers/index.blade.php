<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Drivers</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all registered driver partners</p>
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
            <form method="GET" action="{{ route('admin.drivers.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or phone..."
                           class="w-full pl-10 form-input">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="panel">
            <div class="overflow-x-auto">
                <table class="table-hover">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Service</th>
                            <th class="text-center">Online</th>
                            <th class="text-center">Verified</th>
                            <th class="text-center w-44">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>{{ $driver->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($driver->profile_pic)
                                            <img src="{{ $driver->profile_pic }}" alt="{{ $driver->full_name }}" class="object-cover w-8 h-8 rounded-full">
                                        @else
                                            <div class="flex items-center justify-center w-8 h-8 text-xs font-bold text-white rounded-full bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                                                {{ strtoupper(substr($driver->full_name ?? 'D', 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="font-semibold whitespace-nowrap">{{ $driver->full_name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">{{ $driver->country_code }}{{ $driver->phone_number }}</td>
                                <td>{{ $driver->email ?? '-' }}</td>
                                <td>{{ $driver->service_type ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $driver->is_online ? 'bg-success' : 'bg-danger' }}">
                                        {{ $driver->is_online ? 'Online' : 'Offline' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $driver->document_verification ? 'bg-success' : 'bg-warning' }}">
                                        {{ $driver->document_verification ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.drivers.view', $driver->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-md hover:bg-emerald-100 transition dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.drivers.edit', $driver->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#018DBD] bg-[#018DBD]/10 rounded-md hover:bg-[#018DBD]/20 transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>No drivers found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($drivers->hasPages())
                <div class="pt-5 mt-5 border-t border-gray-200 dark:border-gray-700">
                    {{ $drivers->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout.admin>
