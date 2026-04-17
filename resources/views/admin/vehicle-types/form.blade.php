<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.vehicle-types.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($vehicleType) ? 'Edit' : 'Create' }} Vehicle Type</h2>
    </div>

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-inside list-disc"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <div class="panel">
        <form method="POST"
              action="{{ isset($vehicleType) ? route('admin.vehicle-types.update', $vehicleType) : route('admin.vehicle-types.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($vehicleType)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="mb-1 block font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $vehicleType->name ?? '') }}" class="form-input" required />
                </div>

                {{-- Service --}}
                <div>
                    <label for="service_id" class="mb-1 block font-semibold">Service <span class="text-danger">*</span></label>
                    <select id="service_id" name="service_id" class="form-select" required>
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $vehicleType->service_id ?? '') == $service->id ? 'selected' : '' }}>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Front Image --}}
                <div>
                    <label for="front_image" class="mb-1 block font-semibold">Front Image</label>
                    <input id="front_image" type="file" name="front_image" class="form-input" accept="image/*" />
                    @if(isset($vehicleType) && $vehicleType->front_image)
                        <img src="{{ storage_url($vehicleType->front_image) }}" alt="Front Image" class="mt-2 h-16 w-16 rounded object-cover" />
                    @endif
                </div>

                {{-- Back Image --}}
                <div>
                    <label for="back_image" class="mb-1 block font-semibold">Back Image</label>
                    <input id="back_image" type="file" name="back_image" class="form-input" accept="image/*" />
                    @if(isset($vehicleType) && $vehicleType->back_image)
                        <img src="{{ storage_url($vehicleType->back_image) }}" alt="Back Image" class="mt-2 h-16 w-16 rounded object-cover" />
                    @endif
                </div>
            </div>

            {{-- Checkboxes --}}
            <div class="mt-5">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="enable" value="0" />
                    <input type="checkbox" name="enable" value="1" class="form-checkbox"
                        {{ old('enable', $vehicleType->enable ?? false) ? 'checked' : '' }} />
                    <span>Enable</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.vehicle-types.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0">
                    {{ isset($vehicleType) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
