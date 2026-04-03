<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.coupons.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($coupon) ? 'Edit Coupon' : 'Add Coupon' }}</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="title" class="block mb-2 font-semibold">Title <span class="text-danger">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $coupon->title ?? '') }}"
                           class="form-input" placeholder="Enter coupon title" required />
                </div>

                <div>
                    <label for="code" class="block mb-2 font-semibold">Code <span class="text-danger">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}"
                           class="form-input" placeholder="Enter coupon code" required />
                </div>

                <div>
                    <label for="amount" class="block mb-2 font-semibold">Amount <span class="text-danger">*</span></label>
                    <input id="amount" type="number" step="0.01" name="amount" value="{{ old('amount', $coupon->amount ?? '') }}"
                           class="form-input" placeholder="Enter amount" required />
                </div>

                <div>
                    <label for="type" class="block mb-2 font-semibold">Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="fix" {{ old('type', $coupon->type ?? '') === 'fix' ? 'selected' : '' }}>Fixed</option>
                        <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>

                <div>
                    <label for="validity" class="block mb-2 font-semibold">Validity</label>
                    <input id="validity" type="date" name="validity"
                           value="{{ old('validity', isset($coupon->validity) ? \Carbon\Carbon::parse($coupon->validity)->format('Y-m-d') : '') }}"
                           class="form-input" />
                </div>

                <div class="flex flex-col justify-end gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $coupon->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_public" value="0" />
                        <input type="checkbox" name="is_public" value="1" class="form-checkbox"
                               {{ old('is_public', $coupon->is_public ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Is Public</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($coupon) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
