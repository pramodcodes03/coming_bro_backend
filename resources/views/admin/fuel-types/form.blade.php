<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.fuel-types.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($fuelType) ? 'Edit Fuel Type' : 'Add Fuel Type' }}</h2>
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
              action="{{ isset($fuelType) ? route('admin.fuel-types.update', $fuelType) : route('admin.fuel-types.store') }}">
            @csrf
            @if(isset($fuelType)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $fuelType->name ?? '') }}"
                           class="form-input" placeholder="Enter fuel type name" required />
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $fuelType->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($fuelType) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.fuel-types.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
