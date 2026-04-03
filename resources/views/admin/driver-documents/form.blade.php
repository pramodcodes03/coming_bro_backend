<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.driver-documents.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($document) ? 'Edit Driver Document' : 'Add Driver Document' }}</h2>
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
              action="{{ isset($document) ? route('admin.driver-documents.update', $document) : route('admin.driver-documents.store') }}">
            @csrf
            @if(isset($document)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="title" class="block mb-2 font-semibold">Title <span class="text-danger">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title', $document->title ?? '') }}"
                           class="form-input" placeholder="Enter document title" required />
                </div>

                <div class="md:col-span-2 flex flex-wrap gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="front_side" value="0" />
                        <input type="checkbox" name="front_side" value="1" class="form-checkbox"
                               {{ old('front_side', $document->front_side ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Front Side</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="back_side" value="0" />
                        <input type="checkbox" name="back_side" value="1" class="form-checkbox"
                               {{ old('back_side', $document->back_side ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Back Side</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="expire_at" value="0" />
                        <input type="checkbox" name="expire_at" value="1" class="form-checkbox"
                               {{ old('expire_at', $document->expire_at ?? false) ? 'checked' : '' }} />
                        <span class="font-semibold">Has Expiry</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="enable" value="0" />
                        <input type="checkbox" name="enable" value="1" class="form-checkbox"
                               {{ old('enable', $document->enable ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Enable</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($document) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.driver-documents.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
