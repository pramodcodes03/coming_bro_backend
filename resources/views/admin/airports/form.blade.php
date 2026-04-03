<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.airports.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($airport) ? 'Edit' : 'Create' }} Airport</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($airport) ? route('admin.airports.update', $airport) : route('admin.airports.store') }}">
            @csrf
            @if(isset($airport)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Airport Name --}}
                <div>
                    <label for="airport_name" class="mb-1 block font-semibold">Airport Name <span class="text-danger">*</span></label>
                    <input id="airport_name" type="text" name="airport_name" value="{{ old('airport_name', $airport->airport_name ?? '') }}" class="form-input" required />
                </div>

                {{-- Airport Latitude --}}
                <div>
                    <label for="airport_lat" class="mb-1 block font-semibold">Latitude</label>
                    <input id="airport_lat" type="text" name="airport_lat" value="{{ old('airport_lat', $airport->airport_lat ?? '') }}" class="form-input" />
                </div>

                {{-- Airport Longitude --}}
                <div>
                    <label for="airport_lng" class="mb-1 block font-semibold">Longitude</label>
                    <input id="airport_lng" type="text" name="airport_lng" value="{{ old('airport_lng', $airport->airport_lng ?? '') }}" class="form-input" />
                </div>

                {{-- City Location --}}
                <div>
                    <label for="city_location" class="mb-1 block font-semibold">City</label>
                    <input id="city_location" type="text" name="city_location" value="{{ old('city_location', $airport->city_location ?? '') }}" class="form-input" />
                </div>

                {{-- State --}}
                <div>
                    <label for="state" class="mb-1 block font-semibold">State</label>
                    <input id="state" type="text" name="state" value="{{ old('state', $airport->state ?? '') }}" class="form-input" />
                </div>

                {{-- Country --}}
                <div>
                    <label for="country" class="mb-1 block font-semibold">Country</label>
                    <input id="country" type="text" name="country" value="{{ old('country', $airport->country ?? '') }}" class="form-input" />
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="enable" value="0" />
                    <input type="checkbox" name="enable" value="1" class="form-checkbox"
                        {{ old('enable', $airport->enable ?? false) ? 'checked' : '' }} />
                    <span>Enable</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.airports.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($airport) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
