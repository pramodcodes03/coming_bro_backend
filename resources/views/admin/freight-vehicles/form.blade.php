<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.freight-vehicles.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($freightVehicle) ? 'Edit' : 'Create' }} Freight Vehicle</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($freightVehicle) ? route('admin.freight-vehicles.update', $freightVehicle) : route('admin.freight-vehicles.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($freightVehicle)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="mb-1 block font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $freightVehicle->name ?? '') }}" class="form-input" required />
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="mb-1 block font-semibold">Image</label>
                    <input id="image" type="file" name="image" class="form-input" accept="image/*" />
                    @if(isset($freightVehicle) && $freightVehicle->image)
                        <img src="{{ $freightVehicle->image }}" alt="Vehicle Image" class="mt-2 h-16 w-16 rounded object-cover" />
                    @endif
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block font-semibold">Description</label>
                    <textarea id="description" name="description" rows="3" class="form-input">{{ old('description', $freightVehicle->description ?? '') }}</textarea>
                </div>

                {{-- Length --}}
                <div>
                    <label for="length" class="mb-1 block font-semibold">Length</label>
                    <input id="length" type="number" step="0.01" name="length" value="{{ old('length', $freightVehicle->length ?? '') }}" class="form-input" />
                </div>

                {{-- Width --}}
                <div>
                    <label for="width" class="mb-1 block font-semibold">Width</label>
                    <input id="width" type="number" step="0.01" name="width" value="{{ old('width', $freightVehicle->width ?? '') }}" class="form-input" />
                </div>

                {{-- Height --}}
                <div>
                    <label for="height" class="mb-1 block font-semibold">Height</label>
                    <input id="height" type="number" step="0.01" name="height" value="{{ old('height', $freightVehicle->height ?? '') }}" class="form-input" />
                </div>

                {{-- Weight --}}
                <div>
                    <label for="weight" class="mb-1 block font-semibold">Weight</label>
                    <input id="weight" type="number" step="0.01" name="weight" value="{{ old('weight', $freightVehicle->weight ?? '') }}" class="form-input" />
                </div>

                {{-- KM Charge --}}
                <div>
                    <label for="km_charge" class="mb-1 block font-semibold">KM Charge</label>
                    <input id="km_charge" type="number" step="0.01" name="km_charge" value="{{ old('km_charge', $freightVehicle->km_charge ?? '') }}" class="form-input" />
                </div>

                {{-- Basic Fare KM --}}
                <div>
                    <label for="basic_fare_km" class="mb-1 block font-semibold">Basic Fare KM</label>
                    <input id="basic_fare_km" type="number" step="0.01" name="basic_fare_km" value="{{ old('basic_fare_km', $freightVehicle->basic_fare_km ?? '') }}" class="form-input" />
                </div>

                {{-- Basic Fare Charges --}}
                <div>
                    <label for="basic_fare_charges" class="mb-1 block font-semibold">Basic Fare Charges</label>
                    <input id="basic_fare_charges" type="number" step="0.01" name="basic_fare_charges" value="{{ old('basic_fare_charges', $freightVehicle->basic_fare_charges ?? '') }}" class="form-input" />
                </div>

                {{-- Holding Charge Minute --}}
                <div>
                    <label for="holding_charge_minute" class="mb-1 block font-semibold">Holding Charge Minute</label>
                    <input id="holding_charge_minute" type="number" step="0.01" name="holding_charge_minute" value="{{ old('holding_charge_minute', $freightVehicle->holding_charge_minute ?? '') }}" class="form-input" />
                </div>

                {{-- Holding Charges --}}
                <div>
                    <label for="holding_charges" class="mb-1 block font-semibold">Holding Charges</label>
                    <input id="holding_charges" type="number" step="0.01" name="holding_charges" value="{{ old('holding_charges', $freightVehicle->holding_charges ?? '') }}" class="form-input" />
                </div>

                {{-- Loading/Unloading Charges --}}
                <div>
                    <label for="loading_unloading_charges" class="mb-1 block font-semibold">Loading/Unloading Charges</label>
                    <input id="loading_unloading_charges" type="number" step="0.01" name="loading_unloading_charges" value="{{ old('loading_unloading_charges', $freightVehicle->loading_unloading_charges ?? '') }}" class="form-input" />
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="enable" value="0" />
                    <input type="checkbox" name="enable" value="1" class="form-checkbox"
                        {{ old('enable', $freightVehicle->enable ?? false) ? 'checked' : '' }} />
                    <span>Enable</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.freight-vehicles.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($freightVehicle) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
