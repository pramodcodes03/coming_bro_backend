<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.recharge-plans.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($plan) ? 'Edit Recharge Plan' : 'Add Recharge Plan' }}</h2>
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
              action="{{ isset($plan) ? route('admin.recharge-plans.update', $plan) : route('admin.recharge-plans.store') }}">
            @csrf
            @if(isset($plan)) @method('PUT') @endif

            {{-- ── Basic Details ── --}}
            <h3 class="text-lg font-semibold mb-4 text-primary">Plan Details</h3>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="label" class="block mb-2 font-semibold">Label <span class="text-danger">*</span></label>
                    <input id="label" type="text" name="label" value="{{ old('label', $plan->label ?? '') }}"
                           class="form-input" placeholder="e.g. Daily Ride (Regular)" required />
                </div>

                <div>
                    <label for="price" class="block mb-2 font-semibold">Price (&#8377;) <span class="text-danger">*</span></label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $plan->price ?? '') }}"
                           class="form-input" placeholder="49.00" required />
                </div>

                <div>
                    <label for="original_price" class="block mb-2 font-semibold">Original Price (&#8377;) <span class="text-danger">*</span></label>
                    <input id="original_price" type="number" step="0.01" name="original_price" value="{{ old('original_price', $plan->original_price ?? '') }}"
                           class="form-input" placeholder="99.00" required />
                </div>

                <div>
                    <label for="discount_pct" class="block mb-2 font-semibold">Discount %</label>
                    <input id="discount_pct" type="number" name="discount_pct" value="{{ old('discount_pct', $plan->discount_pct ?? 0) }}"
                           class="form-input" placeholder="50" min="0" max="100" />
                </div>

                <div>
                    <label for="sort_order" class="block mb-2 font-semibold">Sort Order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                           class="form-input" placeholder="1" min="0" />
                </div>

                <div class="flex items-end gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                               {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Active</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_best_value" value="0" />
                        <input type="checkbox" name="is_best_value" value="1" class="form-checkbox"
                               {{ old('is_best_value', $plan->is_best_value ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Best Value Badge</span>
                    </label>
                </div>
            </div>

            {{-- ── Benefits ── --}}
            <h3 class="text-lg font-semibold mb-4 mt-8 text-primary">Benefits</h3>
            <p class="text-sm text-gray-500 mb-3">Add benefits shown on the plan card. Icon names: directions_car_rounded, all_inclusive_rounded, local_offer_rounded, loop_rounded, calendar_month_rounded, people_alt_rounded, star_rounded</p>

            <div id="benefits-container" class="space-y-3">
                @php $benefits = old('benefits', $plan->benefits ?? [['icon' => '', 'title' => '', 'subtitle' => '']]); @endphp
                @foreach($benefits as $i => $benefit)
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 items-end benefit-row">
                        <div class="md:col-span-3">
                            @if($loop->first)
                                <label class="block mb-2 font-semibold text-sm">Icon Name</label>
                            @endif
                            <input type="text" name="benefits[{{ $i }}][icon]" value="{{ $benefit['icon'] ?? '' }}"
                                   class="form-input" placeholder="e.g. star_rounded" />
                        </div>
                        <div class="md:col-span-3">
                            @if($loop->first)
                                <label class="block mb-2 font-semibold text-sm">Title <span class="text-danger">*</span></label>
                            @endif
                            <input type="text" name="benefits[{{ $i }}][title]" value="{{ $benefit['title'] ?? '' }}"
                                   class="form-input" placeholder="e.g. 10 Rides" required />
                        </div>
                        <div class="md:col-span-5">
                            @if($loop->first)
                                <label class="block mb-2 font-semibold text-sm">Subtitle</label>
                            @endif
                            <input type="text" name="benefits[{{ $i }}][subtitle]" value="{{ $benefit['subtitle'] ?? '' }}"
                                   class="form-input" placeholder="e.g. Accept & complete up to 10 rides" />
                        </div>
                        <div class="md:col-span-1">
                            @if(!$loop->first)
                                <button type="button" onclick="this.closest('.benefit-row').remove()" class="btn btn-sm btn-outline-danger px-2 py-1.5">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" /></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addBenefit()" class="btn btn-sm btn-outline-primary mt-3">+ Add Benefit</button>

            {{-- ── Terms & Conditions ── --}}
            <h3 class="text-lg font-semibold mb-4 mt-8 text-primary">Terms & Conditions</h3>
            <div class="space-y-4">
                <div>
                    <label for="terms_title" class="block mb-2 font-semibold">Terms Title</label>
                    <input id="terms_title" type="text" name="terms_title" value="{{ old('terms_title', $plan->terms_title ?? '') }}"
                           class="form-input" placeholder="e.g. 10 Rides Launch Offer - Terms & Conditions" />
                </div>

                <div>
                    <label class="block mb-2 font-semibold">Terms Points</label>
                    <div id="terms-container" class="space-y-2">
                        @php $termsPoints = old('terms_points', $plan->terms_points ?? ['']); @endphp
                        @foreach($termsPoints as $j => $point)
                            <div class="flex gap-2 items-center term-row">
                                <input type="text" name="terms_points[{{ $j }}]" value="{{ $point }}"
                                       class="form-input flex-1" placeholder="Enter a term point..." />
                                @if(!$loop->first)
                                    <button type="button" onclick="this.closest('.term-row').remove()" class="btn btn-sm btn-outline-danger px-2 py-1.5">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" /></svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addTerm()" class="btn btn-sm btn-outline-primary mt-2">+ Add Point</button>
                </div>

                <div>
                    <label for="terms_footer" class="block mb-2 font-semibold">Terms Footer</label>
                    <textarea id="terms_footer" name="terms_footer" rows="3" class="form-textarea"
                              placeholder="e.g. By purchasing this plan, the driver confirms...">{{ old('terms_footer', $plan->terms_footer ?? '') }}</textarea>
                </div>
            </div>

            {{-- ── Submit ── --}}
            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($plan) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.recharge-plans.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    let benefitIndex = {{ count($benefits) }};
    let termIndex = {{ count($termsPoints) }};

    function addBenefit() {
        const container = document.getElementById('benefits-container');
        const html = `
            <div class="grid grid-cols-1 gap-3 md:grid-cols-12 items-end benefit-row">
                <div class="md:col-span-3">
                    <input type="text" name="benefits[${benefitIndex}][icon]" class="form-input" placeholder="e.g. star_rounded" />
                </div>
                <div class="md:col-span-3">
                    <input type="text" name="benefits[${benefitIndex}][title]" class="form-input" placeholder="e.g. 10 Rides" required />
                </div>
                <div class="md:col-span-5">
                    <input type="text" name="benefits[${benefitIndex}][subtitle]" class="form-input" placeholder="e.g. Accept & complete up to 10 rides" />
                </div>
                <div class="md:col-span-1">
                    <button type="button" onclick="this.closest('.benefit-row').remove()" class="btn btn-sm btn-outline-danger px-2 py-1.5">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        benefitIndex++;
    }

    function addTerm() {
        const container = document.getElementById('terms-container');
        const html = `
            <div class="flex gap-2 items-center term-row">
                <input type="text" name="terms_points[${termIndex}]" class="form-input flex-1" placeholder="Enter a term point..." />
                <button type="button" onclick="this.closest('.term-row').remove()" class="btn btn-sm btn-outline-danger px-2 py-1.5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" /></svg>
                </button>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        termIndex++;
    }
</script>
</x-layout.admin>
