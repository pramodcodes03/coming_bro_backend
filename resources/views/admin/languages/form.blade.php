<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.languages.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($language) ? 'Edit Language' : 'Add Language' }}</h2>
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
              action="{{ isset($language) ? route('admin.languages.update', $language) : route('admin.languages.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($language)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $language->name ?? '') }}"
                           class="form-input" placeholder="e.g. English" required />
                </div>

                <div>
                    <label for="code" class="block mb-2 font-semibold">Code <span class="text-danger">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code', $language->code ?? '') }}"
                           class="form-input" placeholder="e.g. en" required />
                </div>

                <div>
                    <label for="image" class="block mb-2 font-semibold">Image (Flag)</label>
                    @if(isset($language) && $language->image)
                        <div class="mb-3">
                            <img src="{{ storage_url($language->image) }}" alt="{{ $language->name }}" class="w-10 h-10 object-cover rounded border" />
                        </div>
                    @endif
                    <input id="image" type="file" name="image" accept="image/*"
                           class="form-input file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:bg-[#018DBD]/10 file:text-[#018DBD] file:font-semibold hover:file:bg-[#018DBD]/20" />
                </div>

                <div class="flex flex-col justify-end gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $language->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_rtl" value="0" />
                        <input type="checkbox" name="is_rtl" value="1" class="form-checkbox"
                               {{ old('is_rtl', $language->is_rtl ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">RTL (Right to Left)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($language) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.languages.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
