<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.banners.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($banner) ? 'Edit Banner' : 'Add Banner' }}</h2>
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
              action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($banner)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="image" class="block mb-2 font-semibold">Image <span class="text-danger">*</span></label>
                    @if(isset($banner) && $banner->image)
                        <div class="mb-3">
                            <img src="{{ storage_url($banner->image) }}" alt="Banner" id="imagePreview"
                                 class="w-64 h-36 object-cover rounded-lg border" />
                        </div>
                    @else
                        <div class="mb-3">
                            <img src="" alt="Preview" id="imagePreview"
                                 class="w-64 h-36 object-cover rounded-lg border hidden" />
                        </div>
                    @endif
                    <input id="image" type="file" name="image" accept="image/*"
                           {{ !isset($banner) ? 'required' : '' }}
                           onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>{const p=document.getElementById('imagePreview');p.src=e.target.result;p.classList.remove('hidden')};r.readAsDataURL(this.files[0])}"
                           class="form-input file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:bg-[#018DBD]/10 file:text-[#018DBD] file:font-semibold hover:file:bg-[#018DBD]/20" />
                </div>

                <div>
                    <label for="position" class="block mb-2 font-semibold">Position <span class="text-danger">*</span></label>
                    <input id="position" type="number" name="position" min="0"
                           value="{{ old('position', $banner->position ?? 0) }}"
                           class="form-input" placeholder="Display order" required />
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $banner->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($banner) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
