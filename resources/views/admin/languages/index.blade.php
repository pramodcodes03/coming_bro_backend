<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">Languages</h2>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.languages.index') }}" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search languages..."
                    class="form-input w-64 pr-10" />
            </form>
            <a href="{{ route('admin.languages.create') }}"
                class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">+ Add New</a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center rounded bg-success/20 p-3.5 text-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="panel">
        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>RTL</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($languages as $language)
                        <tr>
                            <td>{{ $loop->iteration + ($languages->currentPage() - 1) * $languages->perPage() }}</td>
                            <td class="font-semibold">{{ $language->name }}</td>
                            <td><code class="px-2 py-1 text-xs font-bold rounded bg-gray-100 dark:bg-gray-700">{{ $language->code }}</code></td>
                            <td>
                                <span class="badge {{ $language->is_rtl ? 'bg-warning' : 'bg-info' }}">
                                    {{ $language->is_rtl ? 'RTL' : 'LTR' }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.languages.toggle-status', $language) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="badge {{ $language->enable ? 'bg-success' : 'bg-danger' }}">
                                        {{ $language->enable ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.languages.edit', $language) }}" class="hover:text-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.languages.destroy', $language) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this language?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="hover:text-danger">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                <line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No languages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($languages->hasPages())
            <div class="mt-4">{{ $languages->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
