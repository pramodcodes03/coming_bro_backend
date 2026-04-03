<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.cities.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($city) ? 'Edit' : 'Create' }} City</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($city) ? route('admin.cities.update', $city) : route('admin.cities.store') }}">
            @csrf
            @if(isset($city)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="mb-1 block font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $city->name ?? '') }}" class="form-input" required />
                </div>

                {{-- State --}}
                <div>
                    <label for="state_id" class="mb-1 block font-semibold">State <span class="text-danger">*</span></label>
                    <select id="state_id" name="state_id" class="form-select" required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ old('state_id', $city->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                {{ $state->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="enable" value="0" />
                    <input type="checkbox" name="enable" value="1" class="form-checkbox"
                        {{ old('enable', $city->enable ?? false) ? 'checked' : '' }} />
                    <span>Enable</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($city) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
