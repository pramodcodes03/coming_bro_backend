<x-layout.admin>
<div class="space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-bold">SOS Contacts</h2>
    </div>

    @if(session('success'))
        <div class="flex items-center rounded bg-success/20 p-3.5 text-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-center rounded bg-danger/20 p-3.5 text-danger">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add New Contact -->
    <div class="panel">
        <h3 class="mb-4 text-lg font-semibold">Add New Contact</h3>
        <form method="POST" action="{{ route('admin.sos.store') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            @csrf
            <div class="flex-1">
                <label for="name" class="block mb-2 font-semibold">Name <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="form-input" placeholder="Contact name" required />
            </div>
            <div class="flex-1">
                <label for="phone" class="block mb-2 font-semibold">Phone Number <span class="text-danger">*</span></label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                       class="form-input" placeholder="Phone number" required />
            </div>
            <div>
                <button type="submit"
                        class="btn bg-gradient-to-r from-[#018DBD] to-[#13C3C3] text-white border-0 shadow-lg">
                    Add Contact
                </button>
            </div>
        </form>
    </div>

    <!-- Contacts List -->
    <div class="panel">
        <h3 class="mb-4 text-lg font-semibold">Emergency Contacts</h3>
        <div class="overflow-x-auto">
            <table class="table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $index => $contact)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-semibold">{{ $contact['name'] ?? '-' }}</td>
                            <td>{{ $contact['phone'] ?? '-' }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.sos.store') }}" class="inline"
                                      onsubmit="return confirm('Are you sure you want to remove this contact?')">
                                    @csrf
                                    <input type="hidden" name="_action" value="delete" />
                                    <input type="hidden" name="index" value="{{ $index }}" />
                                    <button type="submit" class="hover:text-danger">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            <line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No SOS contacts configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-layout.admin>
