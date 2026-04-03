<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.onboarding.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($screen) ? 'Edit Onboarding Screen' : 'Add Onboarding Screen' }}</h2>
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
              action="{{ isset($screen) ? route('admin.onboarding.update', $screen) : route('admin.onboarding.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($screen)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="title" class="block mb-2 font-semibold">Title <span class="text-danger">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $screen->title ?? '') }}"
                           class="form-input" placeholder="Enter screen title" required />
                </div>

                <div>
                    <label for="type" class="block mb-2 font-semibold">Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="driverApp" {{ old('type', $screen->type ?? '') === 'driverApp' ? 'selected' : '' }}>Driver App</option>
                        <option value="customerApp" {{ old('type', $screen->type ?? '') === 'customerApp' ? 'selected' : '' }}>Customer App</option>
                    </select>
                </div>

                <div>
                    <label for="order" class="block mb-2 font-semibold">Order <span class="text-danger">*</span></label>
                    <input id="order" type="number" name="order" min="0"
                           value="{{ old('order', $screen->order ?? 0) }}"
                           class="form-input" placeholder="Display order" required />
                </div>

                <div>
                    <label for="image" class="block mb-2 font-semibold">Image</label>
                    @if(isset($screen) && $screen->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $screen->image) }}" alt="Screen Image" class="w-24 h-24 object-cover rounded-lg border" />
                        </div>
                    @endif
                    <input id="image" type="file" name="image" accept="image/*"
                           class="form-input file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:bg-[#018DBD]/10 file:text-[#018DBD] file:font-semibold hover:file:bg-[#018DBD]/20" />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block mb-2 font-semibold">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-textarea"
                              placeholder="Enter screen description">{{ old('description', $screen->description ?? '') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                               {{ old('is_active', $screen->is_active ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($screen) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.onboarding.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
