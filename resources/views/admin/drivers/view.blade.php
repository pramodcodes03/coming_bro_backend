<x-layout.admin>
    <div class="space-y-6" x-data="{ open: false, rides: 1 }">
        @if(session('success'))
            <div class="flex items-center gap-2 rounded-lg bg-success/15 border border-success/30 p-3.5 text-success">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="flex items-center gap-2 rounded-lg bg-danger/15 border border-danger/30 p-3.5 text-danger">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.drivers.index') }}" class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Driver Details</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Viewing profile for {{ $driver->full_name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="open = true"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 shadow-sm hover:opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Give Free Rides
                </button>
                <a href="{{ route('admin.drivers.edit', $driver->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3] shadow-sm hover:opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Driver
                </a>
            </div>
        </div>

        <!-- Status Badges Row -->
        <div class="flex flex-wrap gap-3">
            <span class="badge {{ $driver->is_online ? 'bg-success' : 'bg-danger' }}">
                {{ $driver->is_online ? 'Online' : 'Offline' }}
            </span>
            <span class="badge {{ $driver->document_verification ? 'bg-success' : 'bg-warning' }}">
                {{ $driver->document_verification ? 'Documents Verified' : 'Documents Pending' }}
            </span>
            @if($driver->is_subscription_enable)
                <span class="badge bg-primary">Subscription Active</span>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Panel 1: Personal Information -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Personal Information</h3>
                </div>

                <div class="flex flex-col items-center gap-4 p-4 mb-5 rounded-lg sm:flex-row bg-gray-50 dark:bg-gray-800/50">
                    @if($driver->profile_pic)
                        <img src="{{ $driver->profile_pic }}" alt="{{ $driver->full_name }}" class="object-cover w-20 h-20 rounded-full ring-4 ring-white dark:ring-gray-700 shadow">
                    @else
                        <div class="flex items-center justify-center w-20 h-20 text-2xl font-bold text-white rounded-full bg-gradient-to-r from-[#018DBD] to-[#13C3C3] ring-4 ring-white dark:ring-gray-700 shadow">
                            {{ strtoupper(substr($driver->full_name ?? 'D', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $driver->full_name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Driver ID: #{{ $driver->id }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->country_code }}{{ $driver->phone_number }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Gender</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($driver->gender ?? '-') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">City</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->city ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">State</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->state ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Address</span>
                        <span class="text-sm font-medium text-right text-gray-900 dark:text-white max-w-[200px]">{{ $driver->address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Vehicle Information -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 5h8M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Vehicle Information</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Vehicle Type</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->vehicle_type ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Vehicle Number</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->vehicle_number ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Vehicle Color</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            @if($driver->vehicle_color)
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-4 h-4 border border-gray-200 rounded-full dark:border-gray-600" style="background-color: {{ $driver->vehicle_color }}"></span>
                                    {{ $driver->vehicle_color }}
                                </span>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Vehicle Model</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->vehicle_model ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Company Name</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->company_name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">RC Number</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->rc_number ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Engine Number</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->engine_number ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Seats</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->seats ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Documents -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Documents</h3>
                </div>

                @if($driver->driverDocument && $driver->driverDocument->documents)
                    <div class="space-y-3">
                        @foreach($driver->driverDocument->documents as $key => $doc)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 text-gray-500 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            @if(is_array($doc))
                                                {{ $doc['name'] ?? $doc['title'] ?? 'Document ' . ($loop->index + 1) }}
                                            @else
                                                Document {{ $loop->index + 1 }}
                                            @endif
                                        </p>
                                        @if(is_array($doc) && isset($doc['number']))
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doc['number'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if(is_array($doc) && isset($doc['image']))
                                    <a href="{{ $doc['image'] }}" target="_blank" class="text-xs font-medium text-[#018DBD] hover:underline">View</a>
                                @elseif(is_string($doc))
                                    <a href="{{ $doc }}" target="_blank" class="text-xs font-medium text-[#018DBD] hover:underline">View</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm">No documents uploaded yet.</p>
                    </div>
                @endif
            </div>

            <!-- Panel 4: Bank Details -->
            <div class="panel">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Bank Details</h3>
                </div>

                @if($driver->bankDetail)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Bank Name</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->bankDetail->bank_name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Account Holder</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->bankDetail->holder_name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Branch</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->bankDetail->branch_name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Account Number</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $driver->bankDetail->account_number ?? '-' }}</span>
                        </div>
                        @if($driver->bankDetail->other_information)
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Other Info</span>
                                <span class="text-sm font-medium text-right text-gray-900 dark:text-white max-w-[200px]">{{ $driver->bankDetail->other_information }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <p class="text-sm">No bank details added yet.</p>
                    </div>
                @endif
            </div>

            <!-- Panel 5: Subscription Info -->
            <div class="panel lg:col-span-2">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Subscription Information</h3>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Subscription Status</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            <span class="badge {{ $driver->is_subscription_enable ? 'bg-success' : 'bg-danger' }}">
                                {{ $driver->is_subscription_enable ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Subscription Amount</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $driver->subscription_amount ? '₹' . number_format($driver->subscription_amount, 2) : '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Start Date</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $driver->subscription_start_date ? $driver->subscription_start_date->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">End Date</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $driver->subscription_end_date ? $driver->subscription_end_date->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Remaining Days</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $driver->subscription_remaining_days ?? '-' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 ring-1 ring-emerald-200 dark:ring-emerald-800/40">
                        <p class="mb-1 text-xs text-emerald-700 dark:text-emerald-300">Remaining Rides</p>
                        <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300">{{ (int) ($driver->remaining_rides ?? 0) }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Free Rides Given (lifetime)</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ (int) ($driver->free_rides_total ?? 0) }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Wallet Balance</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $driver->wallet_amount ? '₹' . number_format($driver->wallet_amount, 2) : '₹0.00' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">Rating</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            @if($driver->reviews_count > 0)
                                {{ number_format($driver->reviews_sum / $driver->reviews_count, 1) }} / 5
                                <span class="text-xs font-normal text-gray-500">({{ $driver->reviews_count }} reviews)</span>
                            @else
                                No reviews yet
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Free Rides History -->
        <div class="panel">
            <div class="flex flex-col gap-3 mb-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 text-white rounded-lg bg-gradient-to-r from-emerald-500 to-green-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Free Rides Granted</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Complimentary rides this driver received from admins</p>
                    </div>
                </div>
                <button type="button" @click="open = true"
                    class="inline-flex items-center self-start gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 shadow-sm hover:opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Give Free Rides
                </button>
            </div>

            @if($freeRideGrants->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="flex items-center justify-center w-14 h-14 mb-3 rounded-full bg-gray-100 dark:bg-gray-800">
                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3l-2.286 6.857L5 12l5.714 2.143L13 21l2.286-6.857L21 12l-5.714-2.143L13 3z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">No free rides granted yet</p>
                    <p class="text-xs text-gray-400">Use the button above to give this driver complimentary rides.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-gray-100 dark:border-gray-700 dark:text-gray-400">
                                <th class="py-2.5 pr-4 font-semibold">Date</th>
                                <th class="py-2.5 pr-4 font-semibold">Rides</th>
                                <th class="py-2.5 pr-4 font-semibold">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($freeRideGrants as $grant)
                                @php
                                    preg_match('/\d+/', (string) $grant->note, $m);
                                    $grantRides = $m[0] ?? '—';
                                @endphp
                                <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/40">
                                    <td class="py-3 pr-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                        {{ optional($grant->created_date)->format('d M Y, h:i A') ?? '-' }}
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                            +{{ $grantRides }} free
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $grant->note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Give Free Rides Modal -->
    <div x-show="open" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         x-transition.opacity>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

        <!-- Dialog -->
        <div class="relative w-full max-w-md overflow-hidden bg-white shadow-2xl dark:bg-gray-900 rounded-2xl"
             x-show="open" x-transition
             @keydown.escape.window="open = false">
            <!-- Gradient header -->
            <div class="relative px-6 py-5 text-white bg-gradient-to-r from-emerald-500 to-green-500">
                <button type="button" @click="open = false"
                    class="absolute p-1 rounded-lg top-4 right-4 hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-full w-11 h-11 bg-white/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3l-2.286 6.857L5 12l5.714 2.143L13 21l2.286-6.857L21 12l-5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Give Free Rides</h3>
                        <p class="text-sm text-white/80">to {{ $driver->full_name }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.drivers.free-rides', $driver->id) }}" class="px-6 py-5 space-y-5">
                @csrf

                <!-- Quick-pick chips -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">How many rides?</label>
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="n in [1, 3, 5, 10]" :key="n">
                            <button type="button" @click="rides = n"
                                :class="rides == n ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:border-emerald-300'"
                                class="py-2.5 text-sm font-bold border rounded-lg transition"
                                x-text="n"></button>
                        </template>
                    </div>
                </div>

                <!-- Custom amount -->
                <div>
                    <label for="free_rides_input" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Or enter a custom amount</label>
                    <div class="relative">
                        <input id="free_rides_input" type="number" name="rides" min="1" max="1000" required
                            x-model.number="rides"
                            class="w-full px-4 py-3 text-lg font-bold text-center text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                        <span class="absolute text-sm text-gray-400 -translate-y-1/2 right-4 top-1/2 pointer-events-none">rides</span>
                    </div>
                </div>

                <!-- Validity -->
                <div>
                    <label for="free_rides_validity" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Validity (days) <span class="font-normal text-gray-400">(0 / blank = never expires)</span></label>
                    <input id="free_rides_validity" type="number" name="validity_days" min="0" max="3650"
                        placeholder="0 = no expiry"
                        class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                </div>

                <!-- Note -->
                <div>
                    <label for="free_rides_note" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Reason / note <span class="font-normal text-gray-400">(optional)</span></label>
                    <input id="free_rides_note" type="text" name="note" maxlength="255"
                        placeholder="e.g. Festival bonus, support compensation…"
                        class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                </div>

                <!-- Live preview -->
                <div class="flex items-center gap-3 p-3.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/40">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-emerald-800 dark:text-emerald-200">
                        Driver will have
                        <span class="font-bold" x-text="{{ (int) ($driver->remaining_rides ?? 0) }} + (parseInt(rides) || 0)"></span>
                        rides remaining (currently {{ (int) ($driver->remaining_rides ?? 0) }}).
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="open = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        :disabled="!rides || rides < 1"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 shadow-sm hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Grant <span x-text="rides || 0"></span> Rides
                    </button>
                </div>
            </form>
        </div>
        <!-- /Give Free Rides Modal -->
    </div>
</x-layout.admin>
