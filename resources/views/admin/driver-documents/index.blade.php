<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">Driver Documents</h2>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.driver-documents.index') }}" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search documents..."
                    class="form-input w-64 pr-10" />
            </form>
            <a href="{{ route('admin.driver-documents.create') }}"
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
                        <th>Title</th>
                        <th>Front Side</th>
                        <th>Back Side</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                            <td class="font-semibold">{{ $document->title }}</td>
                            <td>
                                <span class="badge {{ $document->front_side ? 'bg-success' : 'bg-danger' }}">
                                    {{ $document->front_side ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $document->back_side ? 'bg-success' : 'bg-danger' }}">
                                    {{ $document->back_side ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $document->expire_at ? 'bg-warning' : 'bg-info' }}">
                                    {{ $document->expire_at ? 'Required' : 'Not Required' }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.driver-documents.toggle-status', $document) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="badge {{ $document->enable ? 'bg-success' : 'bg-danger' }}">
                                        {{ $document->enable ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.driver-documents.edit', $document) }}" class="hover:text-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.driver-documents.destroy', $document) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
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
                            <td colspan="7" class="text-center py-4 text-gray-500">No driver documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
            <div class="mt-4">{{ $documents->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
</x-layout.admin>
