<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($service) ? 'Edit' : 'Create' }} Service</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($service)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Title --}}
                <div>
                    <label for="title" class="mb-1 block font-semibold">Title <span class="text-danger">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $service->title ?? '') }}" class="form-input" required />
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="mb-1 block font-semibold">Image</label>
                    <input id="image" type="file" name="image" class="form-input" accept="image/*" />
                    @if(isset($service) && $service->image)
                        <img src="{{ $service->image }}" alt="Service Image" class="mt-2 h-16 w-16 rounded object-cover" />
                    @endif
                </div>

                {{-- Basic Fare Charges --}}
                <div>
                    <label for="basic_fare_charges" class="mb-1 block font-semibold">Basic Fare Charges</label>
                    <input id="basic_fare_charges" type="number" step="0.01" name="basic_fare_charges" value="{{ old('basic_fare_charges', $service->basic_fare_charges ?? '') }}" class="form-input" />
                </div>

                {{-- Basic Fare KM --}}
                <div>
                    <label for="basic_fare_km" class="mb-1 block font-semibold">Basic Fare KM</label>
                    <input id="basic_fare_km" type="number" step="0.01" name="basic_fare_km" value="{{ old('basic_fare_km', $service->basic_fare_km ?? '') }}" class="form-input" />
                </div>

                {{-- KM Charge --}}
                <div>
                    <label for="km_charge" class="mb-1 block font-semibold">KM Charge</label>
                    <input id="km_charge" type="number" step="0.01" name="km_charge" value="{{ old('km_charge', $service->km_charge ?? '') }}" class="form-input" />
                </div>

                {{-- Non-AC KM Charge --}}
                <div>
                    <label for="non_ac_km_charge" class="mb-1 block font-semibold">Non-AC KM Charge</label>
                    <input id="non_ac_km_charge" type="number" step="0.01" name="non_ac_km_charge" value="{{ old('non_ac_km_charge', $service->non_ac_km_charge ?? '') }}" class="form-input" />
                </div>

                {{-- Ride Time Fare Per Minute --}}
                <div>
                    <label for="ride_time_fare_per_minute" class="mb-1 block font-semibold">Ride Time Fare / Minute</label>
                    <input id="ride_time_fare_per_minute" type="number" step="0.01" name="ride_time_fare_per_minute" value="{{ old('ride_time_fare_per_minute', $service->ride_time_fare_per_minute ?? '') }}" class="form-input" />
                </div>

                {{-- Holding Charge Minute --}}
                <div>
                    <label for="holding_charge_minute" class="mb-1 block font-semibold">Holding Charge Minute</label>
                    <input id="holding_charge_minute" type="number" step="0.01" name="holding_charge_minute" value="{{ old('holding_charge_minute', $service->holding_charge_minute ?? '') }}" class="form-input" />
                </div>

                {{-- Holding Charges --}}
                <div>
                    <label for="holding_charges" class="mb-1 block font-semibold">Holding Charges</label>
                    <input id="holding_charges" type="number" step="0.01" name="holding_charges" value="{{ old('holding_charges', $service->holding_charges ?? '') }}" class="form-input" />
                </div>

                {{-- Night Fare Charge --}}
                <div>
                    <label for="night_fare_charge" class="mb-1 block font-semibold">Night Fare Charge</label>
                    <input id="night_fare_charge" type="number" step="0.01" name="night_fare_charge" value="{{ old('night_fare_charge', $service->night_fare_charge ?? '') }}" class="form-input" />
                </div>

                {{-- Start Night Time --}}
                <div>
                    <label for="start_night_time" class="mb-1 block font-semibold">Start Night Time</label>
                    <input id="start_night_time" type="time" name="start_night_time" value="{{ old('start_night_time', $service->start_night_time ?? '') }}" class="form-input" />
                </div>

                {{-- End Night Time --}}
                <div>
                    <label for="end_night_time" class="mb-1 block font-semibold">End Night Time</label>
                    <input id="end_night_time" type="time" name="end_night_time" value="{{ old('end_night_time', $service->end_night_time ?? '') }}" class="form-input" />
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5 grid grid-cols-2 gap-5 md:grid-cols-4">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="enable" value="0" />
                    <input type="checkbox" name="enable" value="1" class="form-checkbox"
                        {{ old('enable', $service->enable ?? false) ? 'checked' : '' }} />
                    <span>Enable</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="offer_rate" value="0" />
                    <input type="checkbox" name="offer_rate" value="1" class="form-checkbox"
                        {{ old('offer_rate', $service->offer_rate ?? false) ? 'checked' : '' }} />
                    <span>Offer Rate</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="intercity_type" value="0" />
                    <input type="checkbox" name="intercity_type" value="1" class="form-checkbox"
                        {{ old('intercity_type', $service->intercity_type ?? false) ? 'checked' : '' }} />
                    <span>Intercity Type</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="is_ac_non_ac" value="0" />
                    <input type="checkbox" name="is_ac_non_ac" value="1" class="form-checkbox"
                        {{ old('is_ac_non_ac', $service->is_ac_non_ac ?? false) ? 'checked' : '' }} />
                    <span>AC / Non-AC</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($service) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
