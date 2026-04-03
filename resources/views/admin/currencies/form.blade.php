<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.currencies.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($currency) ? 'Edit Currency' : 'Add Currency' }}</h2>
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
              action="{{ isset($currency) ? route('admin.currencies.update', $currency) : route('admin.currencies.store') }}">
            @csrf
            @if(isset($currency)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $currency->name ?? '') }}"
                           class="form-input" placeholder="e.g. US Dollar" required />
                </div>

                <div>
                    <label for="symbol" class="block mb-2 font-semibold">Symbol <span class="text-danger">*</span></label>
                    <input id="symbol" type="text" name="symbol" value="{{ old('symbol', $currency->symbol ?? '') }}"
                           class="form-input" placeholder="e.g. $" required />
                </div>

                <div>
                    <label for="code" class="block mb-2 font-semibold">Code <span class="text-danger">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code', $currency->code ?? '') }}"
                           class="form-input" placeholder="e.g. USD" required />
                </div>

                <div>
                    <label for="decimal_digits" class="block mb-2 font-semibold">Decimal Digits</label>
                    <input id="decimal_digits" type="number" name="decimal_digits" min="0" max="10"
                           value="{{ old('decimal_digits', $currency->decimal_digits ?? 2) }}"
                           class="form-input" />
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $currency->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="symbol_at_right" value="0" />
                        <input type="checkbox" name="symbol_at_right" value="1" class="form-checkbox"
                               {{ old('symbol_at_right', $currency->symbol_at_right ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Symbol at Right</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($currency) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.currencies.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
