@extends('layouts.app')
@section('title', 'Kelola Dokumen')

@section('content')

<style>
  #doc-modal{ padding:16px; }
  #doc-modal-box{
    width:100%;
    max-width:680px !important;
    max-height:88vh;
    border-radius:18px !important;
    margin:auto;
  }
  @media (max-width:640px){
    #doc-modal{ padding:0; align-items:flex-end; }
    #doc-modal-box{
      max-width:100% !important;
      max-height:92vh;
      border-radius:22px 22px 0 0 !important;
    }
  }
</style>

    <div class="mb-6 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Kelola Dokumen</h2>
            <p class="text-gray-400 text-sm mt-0.5">Total {{ $documents->total() }} dokumen</p>
        </div>
        <button type="button" onclick="openDocModal()"
                class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shrink-0">
            + Upload Dokumen
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Judul</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Kategori</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Departemen</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Min Grade</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Acknowledge</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800 dark:text-gray-100">{{ $doc->title }}</p>
                                <p class="text-gray-400 text-xs">{{ $doc->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-xs px-2 py-1 rounded-full capitalize whitespace-nowrap">
                                    {{ $doc->category }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                                {{ $doc->department?->name ?? 'Semua' }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">
                                {{ $doc->minGrade?->label() ?? '-' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($doc->requires_acknowledgement)
                                    <a href="{{ route('admin.documents.acknowledgements', $doc->id) }}"
                                       class="inline-flex items-center gap-1 text-xs text-primary-700 dark:text-primary-400 font-medium hover:underline whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        {{ $doc->acknowledgedCount() }} acknowledge
                                    </a>
                                @else
                                    <span class="text-xs text-gray-300 dark:text-gray-600">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if($doc->is_active)
                                    <span class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs px-2 py-1 rounded-full whitespace-nowrap">Aktif</span>
                                @else
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs px-2 py-1 rounded-full whitespace-nowrap">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('admin.documents.toggle', $doc->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs text-blue-500 hover:underline">
                                            {{ $doc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}"
                                          onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400 dark:text-gray-500">
                                Belum ada dokumen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $documents->links() }}
        </div>
    </div>

    {{-- ===================== MODAL UPLOAD DOKUMEN ===================== --}}
    <div id="doc-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black/55 backdrop-blur-[2px]" onclick="closeDocModal()"></div>

        <div id="doc-modal-box"
             class="relative bg-white dark:bg-gray-800 overflow-y-auto shadow-2xl transition-all duration-300 opacity-0"
             style="transform:translateY(20px) scale(.96)">

            {{-- Header --}}
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-5 sm:px-6 py-4 flex items-center justify-between gap-3 z-10">
                <div class="min-w-0">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-base">Upload Dokumen Baru</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi detail dokumen dan pilih file yang akan diunggah</p>
                </div>
                <button type="button" onclick="closeDocModal()"
                        class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center shrink-0 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-5 sm:px-6 py-5">

                @if($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" id="doc-form">
                    @csrf

                    <div class="space-y-4">

                        {{-- Judul --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Dokumen</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                   placeholder="Contoh: SOP Rekrutmen 2026"/>
                        </div>

                        {{-- Kategori + Min Grade --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                <select name="category" id="category" required
                                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="policy"             {{ old('category') === 'policy' ? 'selected' : '' }}>Policy</option>
                                    <option value="roles"              {{ old('category') === 'roles' ? 'selected' : '' }}>Rules &amp; Responsibilities</option>
                                    <option value="knowledge-base"     {{ old('category') === 'knowledge-base' ? 'selected' : '' }}>Knowledge Base</option>
                                    <option value="explicit-knowledge" {{ old('category') === 'explicit-knowledge' ? 'selected' : '' }}>Explicit Knowledge</option>
                                    <option value="tacit-knowledge"    {{ old('category') === 'tacit-knowledge' ? 'selected' : '' }}>Tacit Knowledge</option>
                                    <option value="knowledge-map"      {{ old('category') === 'knowledge-map' ? 'selected' : '' }}>Knowledge Map</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimum Grade</label>
                                <select name="min_grade_id"
                                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                    <option value="">Semua Grade (seluruh karyawan)</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" {{ old('min_grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Grade ini ke atas yang bisa mengakses</p>
                            </div>
                        </div>

                        {{-- Sub Kategori (khusus Rules & Responsibilities) --}}
                        <div id="sub-category-wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Sub Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="sub_category" id="sub_category"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                <option value="">-- Pilih Sub Kategori --</option>
                                <option value="struktur-organisasi" {{ old('sub_category') === 'struktur-organisasi' ? 'selected' : '' }}>Struktur Organisasi</option>
                                <option value="jobdesk" {{ old('sub_category') === 'jobdesk' ? 'selected' : '' }}>Job desc</option>
                            </select>
                        </div>

                        {{-- Tipe Konten --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Konten</label>
                            <select name="type" id="type"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                <option value="pdf"        {{ old('type', 'pdf') === 'pdf' ? 'selected' : '' }}>PDF Dokumen</option>
                                <option value="attachment" {{ old('type') === 'attachment' ? 'selected' : '' }}>Attachment (Word/Excel/PPT/dll)</option>
                                <option value="poster"     {{ old('type') === 'poster' ? 'selected' : '' }}>Poster</option>
                                <option value="info"       {{ old('type') === 'info' ? 'selected' : '' }}>Info / Pengumuman Visual</option>
                                <option value="banner"     {{ old('type') === 'banner' ? 'selected' : '' }}>Banner Dashboard</option>
                            </select>
                        </div>

                        {{-- Departemen --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departemen (opsional)</label>
                            <select name="department_id" id="department_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika dokumen ini untuk semua departemen</p>
                        </div>

                        {{-- Jabatan (muncul kalau departemen dipilih) --}}
                        <div id="position-wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan (opsional)</label>
                            <select name="position_id" id="position_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                                <option value="">Semua Jabatan di Departemen Ini</option>
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Pilih jabatan spesifik jika dokumen hanya untuk jabatan tertentu</p>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi (opsional)</label>
                            <textarea name="description" rows="3"
                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                                      placeholder="Deskripsi singkat dokumen...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Opsi tambahan --}}
                        <div class="space-y-3">
                            <label for="show_on_dashboard"
                                   class="flex items-start gap-3 p-3.5 bg-gray-50 dark:bg-gray-900 rounded-lg cursor-pointer">
                                <input type="checkbox" name="show_on_dashboard" id="show_on_dashboard" value="1"
                                       class="w-4 h-4 mt-0.5 text-primary-700 border-gray-300 rounded shrink-0"
                                       {{ old('show_on_dashboard') ? 'checked' : '' }}>
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">Tampilkan di Dashboard</span>
                                    <span class="text-[11px] text-gray-400">Konten muncul di slider dashboard semua user</span>
                                </div>
                            </label>

                            <label for="requires_acknowledgement"
                                   class="flex items-start gap-3 p-3.5 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-100 dark:border-yellow-800 cursor-pointer">
                                <input type="checkbox" name="requires_acknowledgement" id="requires_acknowledgement" value="1"
                                       class="w-4 h-4 mt-0.5 text-primary-700 border-gray-300 rounded shrink-0"
                                       {{ old('requires_acknowledgement') ? 'checked' : '' }}>
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">Wajib Acknowledgement</span>
                                    <span class="text-[11px] text-gray-400">User wajib klik "Sudah Membaca &amp; Memahami" setelah membaca</span>
                                </div>
                            </label>
                        </div>

                        {{-- File Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Attachment
                                <span class="text-gray-400 font-normal">(maks. 20MB)</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-5 text-center hover:border-primary-300 transition cursor-pointer"
                                 onclick="document.getElementById('file').click()" id="drop-zone">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm text-gray-400" id="file-label">Klik untuk pilih file</p>
                                <p class="text-[11px] text-gray-300 dark:text-gray-600 mt-1">
                                    PDF &middot; DOC &middot; DOCX &middot; XLS &middot; XLSX &middot; PPT &middot; PPTX &middot; JPG &middot; PNG
                                </p>
                                <input type="file" name="file" id="file" required
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                                       class="hidden"
                                       onchange="handleFileChange(this)"/>
                            </div>

                            <div id="file-info" class="hidden mt-3 items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div id="file-icon" class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate" id="file-name"></p>
                                    <p class="text-xs text-gray-400" id="file-size"></p>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-red-500 transition shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 px-5 sm:px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeDocModal()"
                        class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                    Batal
                </button>
                <button type="submit" form="doc-form" id="doc-submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm flex items-center gap-2">
                    <span id="doc-submit-label">Upload Dokumen</span>
                    <svg id="doc-submit-spin" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    const docModal = document.getElementById('doc-modal');
    const docBox   = document.getElementById('doc-modal-box');

    function openDocModal() {
        docModal.classList.remove('hidden');
        docModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function () {
            docBox.classList.remove('opacity-0');
            docBox.style.transform = 'translateY(0) scale(1)';
        });
    }

    function closeDocModal() {
        docBox.classList.add('opacity-0');
        docBox.style.transform = 'translateY(20px) scale(.96)';
        document.body.style.overflow = '';

        setTimeout(function () {
            docModal.classList.add('hidden');
            docModal.classList.remove('flex');
        }, 280);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !docModal.classList.contains('hidden')) closeDocModal();
    });

    // ===== Info file terpilih =====
    const fileColors = {
        pdf:  { bg: '#ef4444', label: 'PDF' },
        doc:  { bg: '#2563eb', label: 'DOC' },
        docx: { bg: '#2563eb', label: 'DOCX' },
        xls:  { bg: '#16a34a', label: 'XLS' },
        xlsx: { bg: '#16a34a', label: 'XLSX' },
        ppt:  { bg: '#ea580c', label: 'PPT' },
        pptx: { bg: '#ea580c', label: 'PPTX' },
        jpg:  { bg: '#7c3aed', label: 'JPG' },
        jpeg: { bg: '#7c3aed', label: 'JPG' },
        png:  { bg: '#0891b2', label: 'PNG' },
    };

    function handleFileChange(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const ext  = file.name.split('.').pop().toLowerCase();
        const info = fileColors[ext] || { bg: '#6b7280', label: ext.toUpperCase() };
        const size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(1) + ' KB'
            : (file.size / (1024 * 1024)).toFixed(1) + ' MB';

        document.getElementById('file-icon').style.background = info.bg;
        document.getElementById('file-icon').textContent      = info.label;
        document.getElementById('file-name').textContent      = file.name;
        document.getElementById('file-size').textContent      = size;
        document.getElementById('file-info').classList.remove('hidden');
        document.getElementById('file-info').classList.add('flex');
        document.getElementById('drop-zone').classList.add('border-primary-300');
        document.getElementById('file-label').textContent = 'File dipilih:';
    }

    function clearFile() {
        document.getElementById('file').value = '';
        document.getElementById('file-info').classList.add('hidden');
        document.getElementById('file-info').classList.remove('flex');
        document.getElementById('drop-zone').classList.remove('border-primary-300');
        document.getElementById('file-label').textContent = 'Klik untuk pilih file';
    }

    // ===== Sub Kategori hanya untuk Rules & Responsibilities =====
    (function () {
        const categorySelect     = document.getElementById('category');
        const subCategoryWrapper = document.getElementById('sub-category-wrapper');
        const subCategorySelect  = document.getElementById('sub_category');

        function toggleSubCategory() {
            if (categorySelect.value === 'roles') {
                subCategoryWrapper.classList.remove('hidden');
            } else {
                subCategoryWrapper.classList.add('hidden');
                subCategorySelect.value = '';
            }
        }

        categorySelect.addEventListener('change', toggleSubCategory);
        toggleSubCategory();
    })();

    // ===== Dropdown Jabatan dependent ke Departemen =====
    (function () {
        const positionsByDept  = @json($positions->groupBy('department_id'));
        const oldPositionId    = '{{ old('position_id') }}';
        const departmentSelect = document.getElementById('department_id');
        const positionWrapper  = document.getElementById('position-wrapper');
        const positionSelect   = document.getElementById('position_id');

        function filterPositions(deptId) {
            positionSelect.innerHTML = '<option value="">Semua Jabatan di Departemen Ini</option>';

            if (!deptId || !positionsByDept[deptId]) {
                positionWrapper.classList.add('hidden');
                return;
            }

            positionsByDept[deptId].forEach(function (pos) {
                const opt = document.createElement('option');
                opt.value = pos.id;
                opt.textContent = pos.name;
                if (String(pos.id) === String(oldPositionId)) opt.selected = true;
                positionSelect.appendChild(opt);
            });

            positionWrapper.classList.remove('hidden');
        }

        filterPositions(departmentSelect.value);
        departmentSelect.addEventListener('change', function () {
            filterPositions(this.value);
        });
    })();

    // Cegah double-submit
    document.getElementById('doc-form').addEventListener('submit', function () {
        const btn = document.getElementById('doc-submit');
        btn.disabled = true;
        document.getElementById('doc-submit-label').textContent = 'Mengunggah';
        document.getElementById('doc-submit-spin').classList.remove('hidden');
    });

    // Kalau validasi gagal, buka modal lagi dengan isian sebelumnya
    @if($errors->any() || old('title'))
        openDocModal();
    @endif
</script>
@endpush