<x-layout.admin>
    <div class="space-y-6"
         x-data="{
            open: false,
            rides: 1,
            driverId: null,
            driverName: '',
            driverRemaining: 0,
            actionUrl: '{{ url('admin/drivers') }}',
            give(id, name, remaining) {
                this.driverId = id;
                this.driverName = name;
                this.driverRemaining = parseInt(remaining) || 0;
                this.rides = 1;
                this.open = true;
            },
            planOpen: false,
            selectedPlanId: '',
            assignAction: '',
            plans: {{ Illuminate\Support\Js::from($rechargePlans->map(fn($p) => [
                'id' => $p->id,
                'label' => $p->label,
                'rides' => (int) $p->rides,
                'price' => (float) $p->price,
                'validity_days' => $p->validity_days ? (int) $p->validity_days : 0,
            ])) }},
            get selectedPlan() { return this.plans.find(p => p.id == this.selectedPlanId) || null; },
            planLabel(p) { return p.label + ' — ' + p.rides + ' rides · ₹' + p.price.toFixed(2) + ' · ' + (p.validity_days > 0 ? p.validity_days + 'd' : 'no expiry'); },
            assignPlan(id, name, remaining) {
                this.driverId = id;
                this.driverName = name;
                this.driverRemaining = parseInt(remaining) || 0;
                this.selectedPlanId = '';
                this.assignAction = this.actionUrl + '/' + id + '/assign-plan';
                this.planOpen = true;
            }
         }">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Drivers</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all registered driver partners</p>
            </div>
            <a href="{{ route('admin.drivers.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filters -->
        @php $hasFilters = collect(['search','name','email','mobile','ip','online','verified','date_from','date_to'])->contains(fn($f) => request()->filled($f)); @endphp
        <div class="panel">
            <form method="GET" action="{{ route('admin.drivers.index') }}" class="space-y-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Quick search by name, phone, email, or IP..."
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
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Online</label>
                        <select name="online" class="form-select">
                            <option value="">All</option>
                            <option value="online" @selected(request('online') === 'online')>Online</option>
                            <option value="offline" @selected(request('online') === 'offline')>Offline</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Verification</label>
                        <select name="verified" class="form-select">
                            <option value="">All</option>
                            <option value="verified" @selected(request('verified') === 'verified')>Verified</option>
                            <option value="pending" @selected(request('verified') === 'pending')>Pending</option>
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
                            <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-primary">Clear</a>
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
                            <th class="text-center w-72">Actions</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>IP Address</th>
                            <th>Service</th>
                            <th class="text-center">Online</th>
                            <th class="text-center">Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>{{ $driver->id }}</td>
                                <td class="text-center">
                                    <div class="grid grid-cols-2 gap-2 w-fit mx-auto">
                                        <a href="{{ route('admin.drivers.view', $driver->id) }}"
                                           class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-md hover:bg-emerald-100 transition dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 whitespace-nowrap">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                        <a href="{{ route('admin.drivers.edit', $driver->id) }}"
                                           class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 text-xs font-medium text-[#018DBD] bg-[#018DBD]/10 rounded-md hover:bg-[#018DBD]/20 transition whitespace-nowrap">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <button type="button"
                                           @click="assignPlan({{ $driver->id }}, @js($driver->full_name), {{ (int) ($driver->remaining_rides ?? 0) }})"
                                           class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 text-xs font-medium text-white rounded-md bg-gradient-to-r from-[#018DBD] to-[#13C3C3] hover:opacity-90 transition whitespace-nowrap">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            Plan
                                        </button>
                                        <button type="button"
                                           @click="give({{ $driver->id }}, @js($driver->full_name), {{ (int) ($driver->remaining_rides ?? 0) }})"
                                           class="inline-flex items-center justify-center gap-1 w-full px-3 py-1.5 text-xs font-medium text-white rounded-md bg-gradient-to-r from-emerald-500 to-green-500 hover:opacity-90 transition whitespace-nowrap">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3l-2.286 6.857L5 12l5.714 2.143L13 21l2.286-6.857L21 12l-5.714-2.143L13 3z"/></svg>
                                            Free Ride
                                        </button>
                                    </div>
                                </td>
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
                                <td class="whitespace-nowrap text-xs">
                                    @if($driver->last_login_ip || $driver->register_ip)
                                        <div><span class="text-gray-400">Last:</span> {{ $driver->last_login_ip ?? '-' }}</div>
                                        <div><span class="text-gray-400">Reg:</span> {{ $driver->register_ip ?? '-' }}</div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-gray-500 dark:text-gray-400">
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

        <!-- Shared Give Free Rides Modal -->
        <div x-show="open" x-cloak
             class="fixed inset-0 z-[60] flex items-center justify-center p-4"
             x-transition.opacity>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

            <div class="relative w-full max-w-md overflow-hidden bg-white shadow-2xl dark:bg-gray-900 rounded-2xl"
                 x-show="open" x-transition @keydown.escape.window="open = false">
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
                            <p class="text-sm text-white/80">to <span x-text="driverName"></span></p>
                        </div>
                    </div>
                </div>

                <form method="POST" :action="`${actionUrl}/${driverId}/free-rides`" class="px-6 py-5 space-y-5">
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
                        <label for="list_free_rides_input" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Or enter a custom amount</label>
                        <div class="relative">
                            <input id="list_free_rides_input" type="number" name="rides" min="1" max="1000" required
                                x-model.number="rides"
                                class="w-full px-4 py-3 text-lg font-bold text-center text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                            <span class="absolute text-sm text-gray-400 -translate-y-1/2 right-4 top-1/2 pointer-events-none">rides</span>
                        </div>
                    </div>

                    <!-- Validity -->
                    <div>
                        <label for="list_free_rides_validity" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Validity (days) <span class="font-normal text-gray-400">(0 / blank = never expires)</span></label>
                        <input id="list_free_rides_validity" type="number" name="validity_days" min="0" max="3650"
                            placeholder="0 = no expiry"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                    </div>

                    <!-- Note -->
                    <div>
                        <label for="list_free_rides_note" class="block mb-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">Reason / note <span class="font-normal text-gray-400">(optional)</span></label>
                        <input id="list_free_rides_note" type="text" name="note" maxlength="255"
                            placeholder="e.g. Festival bonus, support compensation…"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                    </div>

                    <!-- Live preview -->
                    <div class="flex items-center gap-3 p-3.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/40">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-emerald-800 dark:text-emerald-200">
                            Driver will have
                            <span class="font-bold" x-text="driverRemaining + (parseInt(rides) || 0)"></span>
                            rides remaining (currently <span x-text="driverRemaining"></span>).
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
        </div>
        <!-- /Shared Give Free Rides Modal -->

        @include('admin.drivers.partials.assign-plan-modal')
    </div>
</x-layout.admin>
