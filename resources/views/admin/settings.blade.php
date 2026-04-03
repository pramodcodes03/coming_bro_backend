<x-layout.admin>
    <link rel="stylesheet" href="/assets/css/quill.snow.css">
    <style>
        .ql-editor { min-height: 200px; font-size: 14px; }
        .dark .ql-toolbar.ql-snow { border-color: #191e3a; background: #0e1726; }
        .dark .ql-container.ql-snow { border-color: #191e3a; background: #121e32; }
        .dark .ql-editor { color: #e0e6ed; }
        .dark .ql-snow .ql-stroke { stroke: #888ea8; }
        .dark .ql-snow .ql-fill, .dark .ql-snow .ql-stroke.ql-fill { fill: #888ea8; }
        .dark .ql-snow .ql-picker-label { color: #888ea8; }
        .dark .ql-snow .ql-picker-options { background: #1b2e4b; border-color: #191e3a; }
        .dark .ql-snow .ql-picker-item { color: #e0e6ed; }
        .ql-toolbar.ql-snow { border-radius: 6px 6px 0 0; }
        .ql-container.ql-snow { border-radius: 0 0 6px 6px; }
    </style>

    <div class="space-y-6" x-data="settingsPage()">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your application configuration</p>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="flex items-center gap-3 p-4 border rounded-lg border-success/30 bg-success/10">
                <svg class="w-5 h-5 text-success shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="text-sm font-medium text-success">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="flex items-center gap-3 p-4 border rounded-lg border-danger/30 bg-danger/10">
                <svg class="w-5 h-5 text-danger shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm font-medium text-danger">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="panel !p-0">
            <div class="border-b border-gray-200 dark:border-[#191e3a]">
                <nav class="flex px-4 pt-2 -mb-px space-x-1 overflow-x-auto" aria-label="Tabs">
                    @foreach ($settings as $index => $setting)
                        <button type="button"
                            @click="activeTab = '{{ $setting->key }}'"
                            :class="activeTab === '{{ $setting->key }}'
                                ? 'border-primary text-primary font-semibold'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors duration-200">
                            @php
                                $icons = [
                                    'global' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>',
                                    'globalKey' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
                                    'globalValue' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>',
                                    'payment' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>',
                                    'contact_us' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                                    'referral' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
                                    'notification_setting' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>',
                                    'logo' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>',
                                    'adminCommission' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
                                ];
                                $icon = $icons[$setting->key] ?? '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>';
                            @endphp
                            <span class="flex items-center gap-2">
                                {!! $icon !!}
                                {{ ucwords(str_replace(['_', '-'], ' ', $setting->key)) }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @foreach ($settings as $setting)
                    @php
                        $richTextFields = ['privacyPolicy', 'termsAndConditions', 'footerTemplate', 'headerTemplate', 'landingPageTemplate'];
                    @endphp
                    <div x-show="activeTab === '{{ $setting->key }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <form method="POST" action="{{ route('admin.settings.update', $setting->key) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            @if (is_array($setting->value))
                                @php
                                    $hasRichText = false;
                                    $regularFields = [];
                                    $richFields = [];
                                    foreach ($setting->value as $field => $val) {
                                        if (in_array($field, $richTextFields)) {
                                            $richFields[$field] = $val;
                                            $hasRichText = true;
                                        } else {
                                            $regularFields[$field] = $val;
                                        }
                                    }
                                @endphp

                                {{-- Regular Fields Grid --}}
                                @if (count($regularFields) > 0)
                                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($regularFields as $field => $val)
                                            <div class="space-y-1.5">
                                                <label for="setting_{{ $setting->key }}_{{ $field }}" class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                    {{ ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace(['_', '-'], ' ', $field))) }}
                                                </label>

                                                @if (is_bool($val) || $val === '0' || $val === '1' || $val === 'true' || $val === 'false')
                                                    {{-- Toggle Switch --}}
                                                    <div class="flex items-center">
                                                        <label class="relative inline-flex items-center cursor-pointer w-12 h-6">
                                                            <input type="hidden" name="value[{{ $field }}]" value="0">
                                                            <input type="checkbox" name="value[{{ $field }}]" value="1"
                                                                {{ ($val == true || $val === 'true' || $val === '1') ? 'checked' : '' }}
                                                                class="sr-only peer">
                                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                                        </label>
                                                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                                                            {{ ($val == true || $val === 'true' || $val === '1') ? 'Enabled' : 'Disabled' }}
                                                        </span>
                                                    </div>
                                                @elseif (is_array($val))
                                                    <textarea name="value[{{ $field }}]"
                                                        id="setting_{{ $setting->key }}_{{ $field }}"
                                                        class="form-textarea !min-h-[80px] font-mono text-xs" rows="4">{{ json_encode($val, JSON_PRETTY_PRINT) }}</textarea>
                                                @else
                                                    <input type="text" name="value[{{ $field }}]"
                                                        id="setting_{{ $setting->key }}_{{ $field }}"
                                                        value="{{ $val }}"
                                                        class="form-input" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Rich Text Fields --}}
                                @foreach ($richFields as $field => $val)
                                    <div class="space-y-1.5 mt-5">
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace(['_', '-'], ' ', $field))) }}
                                        </label>
                                        <input type="hidden" name="value[{{ $field }}]" id="hidden_{{ $setting->key }}_{{ $field }}">
                                        <div id="editor_{{ $setting->key }}_{{ $field }}" class="quill-editor" style="min-height: 250px;">{!! $val !!}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Value</label>
                                    <textarea name="value" class="form-textarea" rows="4">{{ is_string($setting->value) ? $setting->value : json_encode($setting->value) }}</textarea>
                                </div>
                            @endif

                            <div class="flex items-center justify-end pt-4 border-t border-gray-200 dark:border-[#191e3a]">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-4 h-4 ltr:mr-2 rtl:ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                        <polyline points="17 21 17 13 7 13 7 21"/>
                                        <polyline points="7 3 7 8 15 8"/>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="/assets/js/quill.js"></script>
    <script>
        function settingsPage() {
            return {
                activeTab: '{{ $settings->first()?->key ?? '' }}',
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Quill editors
            document.querySelectorAll('.quill-editor').forEach(function (editorEl) {
                const id = editorEl.id; // editor_{key}_{field}
                const parts = id.replace('editor_', '').split('_');
                const settingKey = parts[0];
                const fieldName = parts.slice(1).join('_');
                const hiddenInput = document.getElementById('hidden_' + settingKey + '_' + fieldName);

                const quill = new Quill('#' + id, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            ['blockquote'],
                            ['link'],
                            ['clean']
                        ]
                    }
                });

                // Set initial value to hidden input
                hiddenInput.value = quill.root.innerHTML;

                // Update hidden input on text change
                quill.on('text-change', function () {
                    hiddenInput.value = quill.root.innerHTML;
                });
            });
        });
    </script>
</x-layout.admin>
