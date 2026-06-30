<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.cancel-reasons.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($reason) ? 'Edit Cancel Reason' : 'Add Cancel Reason' }}</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($reason) ? route('admin.cancel-reasons.update', $reason->id) : route('admin.cancel-reasons.store') }}">
            @csrf
            @if(isset($reason)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="reason" class="block mb-2 font-semibold">Reason <span class="text-danger">*</span></label>
                    <input id="reason" type="text" name="reason" value="{{ old('reason', $reason->reason ?? '') }}"
                           class="form-input" placeholder="e.g. Driver wants cash" required />
                </div>

                <div>
                    <label for="applies_to" class="block mb-2 font-semibold">Applies To <span class="text-danger">*</span></label>
                    <select id="applies_to" name="applies_to" class="form-select" required>
                        @foreach(['customer' => 'Customer app', 'driver' => 'Driver app', 'both' => 'Both apps'] as $val => $label)
                            <option value="{{ $val }}" {{ old('applies_to', $reason->applies_to ?? 'customer') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Which cancel sheet this reason shows on.</p>
                </div>

                <div>
                    <label for="sort_order" class="block mb-2 font-semibold">Sort Order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $reason->sort_order ?? 0) }}"
                           class="form-input" placeholder="1" min="0" />
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                               {{ old('is_active', $reason->is_active ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($reason) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.cancel-reasons.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
