<x-layout.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center justify-center w-10 h-10 text-gray-600 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($customer) ? 'Update customer information' : 'Create a new customer record' }}
                </p>
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
            <form method="POST"
                  action="{{ isset($customer) ? route('admin.customers.update', $customer->id) : route('admin.customers.store') }}">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name"
                               value="{{ old('full_name', $customer->full_name ?? '') }}"
                               class="form-input" placeholder="Enter full name" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $customer->email ?? '') }}"
                               class="form-input" placeholder="Enter email address">
                    </div>

                    <!-- Country Code -->
                    <div>
                        <label for="country_code" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Country Code
                        </label>
                        <input type="text" id="country_code" name="country_code"
                               value="{{ old('country_code', $customer->country_code ?? '+91') }}"
                               class="form-input" placeholder="+91">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="phone_number" name="phone_number"
                               value="{{ old('phone_number', $customer->phone_number ?? '') }}"
                               class="form-input" placeholder="Enter phone number" required>
                    </div>

                    <!-- Is Active -->
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   class="form-checkbox rounded text-[#018DBD] border-gray-300 dark:border-gray-600 focus:ring-[#018DBD]"
                                   {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Active</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">- Customer can use the platform when active</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#018DBD] to-[#13C3C3] shadow-sm hover:opacity-90 transition">
                        {{ isset($customer) ? 'Update Customer' : 'Create Customer' }}
                    </button>
                    <a href="{{ route('admin.customers.index') }}"
                       class="btn btn-outline-primary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout.admin>
