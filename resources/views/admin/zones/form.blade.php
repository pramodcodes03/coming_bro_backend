<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.zones.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($zone) ? 'Edit' : 'Create' }} Zone</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($zone) ? route('admin.zones.update', $zone) : route('admin.zones.store') }}">
            @csrf
            @if(isset($zone)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="mb-1 block font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $zone->name ?? '') }}" class="form-input" required />
                </div>

                {{-- Latitude --}}
                <div>
                    <label for="latitude" class="mb-1 block font-semibold">Latitude</label>
                    <input id="latitude" type="text" name="latitude" value="{{ old('latitude', $zone->latitude ?? '') }}" class="form-input" />
                </div>

                {{-- Longitude --}}
                <div>
                    <label for="longitude" class="mb-1 block font-semibold">Longitude</label>
                    <input id="longitude" type="text" name="longitude" value="{{ old('longitude', $zone->longitude ?? '') }}" class="form-input" />
                </div>

                {{-- Area (JSON) --}}
                <div class="md:col-span-2">
                    <label for="area" class="mb-1 block font-semibold">Area (JSON)</label>
                    <textarea id="area" name="area" rows="5" class="form-input font-mono text-sm" placeholder='[{"lat": 0, "lng": 0}, ...]'>{{ old('area', isset($zone) ? (is_array($zone->area) ? json_encode($zone->area, JSON_PRETTY_PRINT) : $zone->area) : '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Enter zone boundary coordinates as JSON array.</p>
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="publish" value="0" />
                    <input type="checkbox" name="publish" value="1" class="form-checkbox"
                        {{ old('publish', $zone->publish ?? false) ? 'checked' : '' }} />
                    <span>Publish</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.zones.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($zone) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
