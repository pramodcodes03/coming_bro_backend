<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.drivers.index') }}" class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Driver</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update driver information for {{ $driver->full_name }}</p>
            </div>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="p-4 border rounded-lg bg-red-50 border-red-200 dark:bg-red-900/30 dark:border-red-800">
                <div class="flex items-center gap-2 mb-2 text-sm font-semibold text-red-700 dark:text-red-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Please fix the following errors:
                </div>
                <ul class="ml-7 text-sm text-red-600 list-disc dark:text-red-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="panel">
            <form method="POST" action="{{ route('admin.drivers.update', $driver->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name"
                               value="{{ old('full_name', $driver->full_name) }}"
                               class="form-input" placeholder="Enter full name" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $driver->email) }}"
                               class="form-input" placeholder="Enter email address">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Phone Number
                        </label>
                        <input type="text" id="phone_number" name="phone_number"
                               value="{{ old('phone_number', $driver->phone_number) }}"
                               class="form-input" placeholder="Enter phone number">
                    </div>

                    <!-- Service -->
                    <div>
                        <label for="service_id" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Service
                        </label>
                        <select id="service_id" name="service_id" class="form-select">
                            <option value="">Select a service</option>
                            @if(isset($services))
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $driver->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Document Verification -->
                    <div class="flex items-start gap-3 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="document_verification" value="0">
                            <input type="checkbox" name="document_verification" value="1"
                                   class="form-checkbox rounded text-[#018DBD] border-gray-300 dark:border-gray-600 focus:ring-[#018DBD]"
                                   {{ old('document_verification', $driver->document_verification) ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Document Verification</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Mark driver documents as verified</p>
                            </div>
                        </label>
                    </div>

                    <!-- Is Online -->
                    <div class="flex items-start gap-3 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_online" value="0">
                            <input type="checkbox" name="is_online" value="1"
                                   class="form-checkbox rounded text-[#018DBD] border-gray-300 dark:border-gray-600 focus:ring-[#018DBD]"
                                   {{ old('is_online', $driver->is_online) ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Online Status</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Set driver as online and available for rides</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3] shadow-sm hover:opacity-90 transition">
                        Update Driver
                    </button>
                    <a href="{{ route('admin.drivers.index') }}"
                       class="btn btn-outline-primary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout.admin>
