@extends('layouts.app')
@section('title', 'Upload Dokumen')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.documents.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <h2 class="text-lg font-bold text-gray-800">Upload Dokumen Baru</h2>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-5">

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                           placeholder="Contoh: SOP Rekrutmen 2024"/>
                </div>

                {{-- Kategori + Min Grade --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category" id="category"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="policy"         {{ old('category') === 'policy' ? 'selected' : '' }}>Policy</option>
                            <option value="roles"          {{ old('category') === 'roles' ? 'selected' : '' }}>Rules & Responsibilities</option>
                            <option value="knowledge-base" {{ old('category') === 'knowledge-base' ? 'selected' : '' }}>Knowledge Base</option>
                            <option value="explicit-knowledge" {{ old('category') === 'explicit-knowledge' ? 'selected' : '' }}>Explicit Knowledge</option>
                            <option value="tacit-knowledge" {{ old('category') === 'tacit-knowledge' ? 'selected' : '' }}>Tacit Knowledge</option>
                            <option value="knowledge-map" {{ old('category') === 'knowledge-map' ? 'selected' : '' }}>Knowledge Map</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Grade</label>
                        <select name="min_grade_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                            <option value="">Semua Grade (seluruh karyawan)</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ old('min_grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->label() }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">User dengan grade ini ke atas yang bisa mengakses dokumen</p>
                    </div>
                </div>

                {{-- Sub Kategori Roles (muncul kalau pilih Rules & Responsibilities) --}}
                <div id="sub-category-wrapper" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sub Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="sub_category"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Sub Kategori --</option>
                        <option value="struktur-organisasi" {{ old('sub_category') === 'struktur-organisasi' ? 'selected' : '' }}>Struktur Organisasi</option>
                        <option value="jobdesk" {{ old('sub_category') === 'jobdesk' ? 'selected' : '' }}>Job desc</option>
                    </select>
                </div>

                {{-- Tipe Konten --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten</label>
                    <select name="type" id="type"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="pdf"        {{ old('type', 'pdf') === 'pdf'        ? 'selected' : '' }}>PDF Dokumen</option>
                        <option value="attachment" {{ old('type') === 'attachment'        ? 'selected' : '' }}>Attachment (Word/Excel/PPT/dll)</option>
                        <option value="poster"     {{ old('type') === 'poster'            ? 'selected' : '' }}>Poster</option>
                        <option value="info"       {{ old('type') === 'info'              ? 'selected' : '' }}>Info / Pengumuman Visual</option>
                        <option value="banner"     {{ old('type') === 'banner'            ? 'selected' : '' }}>Banner Dashboard</option>
                    </select>
                </div>

                {{-- Show on Dashboard --}}
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="show_on_dashboard" id="show_on_dashboard" value="1"
                           class="w-4 h-4 text-primary-700 border-gray-300 rounded"
                           {{ old('show_on_dashboard') ? 'checked' : '' }}>
                    <div>
                        <label for="show_on_dashboard" class="text-sm font-medium text-gray-700">
                            Tampilkan di Dashboard
                        </label>
                        <p class="text-xs text-gray-400">Konten akan muncul di slider dashboard semua user</p>
                    </div>
                </div>

                {{-- Requires Acknowledgement --}}
                <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <input type="checkbox" name="requires_acknowledgement" id="requires_acknowledgement" value="1"
                        class="w-4 h-4 text-primary-700 border-gray-300 rounded"
                        {{ old('requires_acknowledgement') ? 'checked' : '' }}>
                    <div>
                        <label for="requires_acknowledgement" class="text-sm font-medium text-gray-700">
                            Wajib Acknowledgement
                        </label>
                        <p class="text-xs text-gray-400">User wajib klik "Sudah Membaca & Memahami" setelah membaca dokumen ini</p>
                    </div>
                </div>

                {{-- Departemen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen (opsional)</label>
                    <select name="department_id" id="department_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika dokumen ini untuk semua departemen</p>
                </div>

                {{-- Jabatan (muncul kalau departemen dipilih) --}}
                <div id="position-wrapper" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan (opsional)</label>
                    <select name="position_id" id="position_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">Semua Jabatan di Departemen Ini</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Pilih jabatan spesifik jika dokumen ini hanya untuk jabatan tertentu (misal: Programmer saja di dept IT)</p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"
                              placeholder="Deskripsi singkat dokumen...">{{ old('description') }}</textarea>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Attachment
                        <span class="text-gray-400 font-normal">(PDF, Word, Excel, PPT, JPG, PNG — maks. 20MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-primary-300 transition cursor-pointer"
                         onclick="document.getElementById('file').click()" id="drop-zone">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-gray-400" id="file-label">Klik untuk pilih file (maks. 20MB)</p>
                        <p class="text-xs text-gray-300 mt-1">PDF · DOC · DOCX · XLS · XLSX · PPT · PPTX · JPG · PNG</p>
                        <input type="file" name="file" id="file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                               class="hidden"
                               onchange="handleFileChange(this)"/>
                    </div>

                    {{-- File info setelah dipilih --}}
                    <div id="file-info" class="hidden mt-3 items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div id="file-icon" class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate" id="file-name"></p>
                            <p class="text-xs text-gray-400" id="file-size"></p>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-red-500 transition ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Upload Dokumen
                </button>
                <a href="{{ route('admin.documents.index') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
<script>
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
    document.getElementById('file-label').textContent = 'Klik untuk pilih file (maks. 20MB)';
}

// ── Sub Kategori conditional (muncul kalau pilih Rules & Responsibilities) ──
const categorySelect      = document.getElementById('category');
const subCategoryWrapper  = document.getElementById('sub-category-wrapper');

function toggleSubCategory() {
    if (categorySelect.value === 'roles') {
        subCategoryWrapper.classList.remove('hidden');
    } else {
        subCategoryWrapper.classList.add('hidden');
        document.querySelector('select[name="sub_category"]').value = '';
    }
}

categorySelect.addEventListener('change', toggleSubCategory);
toggleSubCategory();

// ── Dropdown Jabatan Dependent ke Departemen ──────────────────────
const positionsByDept = @json($positions->groupBy('department_id'));
const oldPositionId   = '{{ old('position_id') }}';

const departmentSelect = document.getElementById('department_id');
const positionWrapper  = document.getElementById('position-wrapper');
const positionSelect   = document.getElementById('position_id');

function filterPositions(deptId) {
    positionSelect.innerHTML = '<option value="">Semua Jabatan di Departemen Ini</option>';

    if (!deptId || !positionsByDept[deptId]) {
        positionWrapper.classList.add('hidden');
        return;
    }

    positionsByDept[deptId].forEach(pos => {
        const opt = document.createElement('option');
        opt.value = pos.id;
        opt.textContent = pos.name;
        if (String(pos.id) === String(oldPositionId)) {
            opt.selected = true;
        }
        positionSelect.appendChild(opt);
    });

    positionWrapper.classList.remove('hidden');
}

filterPositions(departmentSelect.value);

departmentSelect.addEventListener('change', function () {
    filterPositions(this.value);
});
</script>
@endpush