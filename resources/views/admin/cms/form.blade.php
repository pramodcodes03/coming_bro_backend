<x-layout.admin>
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" /><path d="M12 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-xl font-bold">{{ isset($page) ? 'Edit Page' : 'Add Page' }}</h2>
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
              action="{{ isset($page) ? route('admin.cms.update', $page) : route('admin.cms.store') }}">
            @csrf
            @if(isset($page)) @method('PUT') @endif

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $page->name ?? '') }}"
                           class="form-input" placeholder="Enter page name" required />
                </div>

                <div>
                    <label for="slug" class="block mb-2 font-semibold">Slug <span class="text-danger">*</span></label>
                    <input id="slug" type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}"
                           class="form-input" placeholder="Enter page slug" required />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block mb-2 font-semibold">Description</label>
                    <textarea id="description" name="description" rows="8" class="form-textarea"
                              placeholder="Enter page content">{{ old('description', $page->description ?? '') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="publish" value="0" />
                        <input type="checkbox" name="publish" value="1" class="form-checkbox"
                               {{ old('publish', $page->publish ?? true) ? 'checked' : '' }} />
                        <span class="font-semibold">Publish</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-8">
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    {{ isset($page) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-layout.admin>
