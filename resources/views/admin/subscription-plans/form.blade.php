<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.subscription-plans.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($plan) ? 'Edit Subscription Plan' : 'Add Subscription Plan' }}</h2>
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
              action="{{ isset($plan) ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($plan)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $plan->name ?? '') }}"
                           class="form-input" placeholder="Enter plan name" required />
                </div>

                <div>
                    <label for="amount" class="block mb-2 font-semibold">Amount <span class="text-danger">*</span></label>
                    <input id="amount" type="number" step="0.01" name="amount" value="{{ old('amount', $plan->amount ?? '') }}"
                           class="form-input" placeholder="Enter amount" required />
                </div>

                <div>
                    <label for="duration" class="block mb-2 font-semibold">Duration (days) <span class="text-danger">*</span></label>
                    <input id="duration" type="number" name="duration" value="{{ old('duration', $plan->duration ?? '') }}"
                           class="form-input" placeholder="Enter duration in days" required />
                </div>

                <div>
                    <label for="ride" class="block mb-2 font-semibold">Rides</label>
                    <input id="ride" type="number" name="ride" value="{{ old('ride', $plan->ride ?? '') }}"
                           class="form-input" placeholder="Number of rides" />
                </div>

                <div>
                    <label for="gst" class="block mb-2 font-semibold">GST (%)</label>
                    <input id="gst" type="number" step="0.01" name="gst" value="{{ old('gst', $plan->gst ?? '') }}"
                           class="form-input" placeholder="Enter GST percentage" />
                </div>

                <div>
                    <label for="tds" class="block mb-2 font-semibold">TDS (%)</label>
                    <input id="tds" type="number" step="0.01" name="tds" value="{{ old('tds', $plan->tds ?? '') }}"
                           class="form-input" placeholder="Enter TDS percentage" />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block mb-2 font-semibold">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-textarea"
                              placeholder="Enter plan description">{{ old('description', $plan->description ?? '') }}</textarea>
                </div>

                <div>
                    <label for="image" class="block mb-2 font-semibold">Image</label>
                    @if(isset($plan) && $plan->image)
                        <div class="mb-3">
                            <img src="{{ storage_url($plan->image) }}" alt="Plan Image" class="w-32 h-32 object-cover rounded-lg border" />
                        </div>
                    @endif
                    <input id="image" type="file" name="image" accept="image/*"
                           class="form-input file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:bg-[#018DBD]/10 file:text-[#018DBD] file:font-semibold hover:file:bg-[#018DBD]/20" />
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $plan->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($plan) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
