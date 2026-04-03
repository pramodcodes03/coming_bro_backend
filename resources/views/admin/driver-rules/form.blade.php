<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.driver-rules.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($rule) ? 'Edit Driver Rule' : 'Add Driver Rule' }}</h2>
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
              action="{{ isset($rule) ? route('admin.driver-rules.update', $rule) : route('admin.driver-rules.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($rule)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $rule->name ?? '') }}"
                           class="form-input" placeholder="Enter rule name" required />
                </div>

                <div>
                    <label for="image" class="block mb-2 font-semibold">Image</label>
                    @if(isset($rule) && $rule->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $rule->image) }}" alt="{{ $rule->name }}" class="w-16 h-16 object-cover rounded-lg border" />
                        </div>
                    @endif
                    <input id="image" type="file" name="image" accept="image/*"
                           class="form-input file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:bg-[#018DBD]/10 file:text-[#018DBD] file:font-semibold hover:file:bg-[#018DBD]/20" />
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $rule->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($rule) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.driver-rules.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
