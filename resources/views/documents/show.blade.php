@extends('layouts.app')
@section('title', $document->title)

@php
    $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
    $isPdf    = $ext === 'pdf';
    $isImage  = in_array($ext, ['jpg', 'jpeg', 'png']);
    $isOffice = in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
    $extIcons = [
        'doc'  => ['label' => 'DOC',  'color' => '#2563eb'],
        'docx' => ['label' => 'DOCX', 'color' => '#2563eb'],
        'xls'  => ['label' => 'XLS',  'color' => '#16a34a'],
        'xlsx' => ['label' => 'XLSX', 'color' => '#16a34a'],
        'ppt'  => ['label' => 'PPT',  'color' => '#ea580c'],
        'pptx' => ['label' => 'PPTX', 'color' => '#ea580c'],
        'pdf'  => ['label' => 'PDF',  'color' => '#ef4444'],
    ];
    $fileInfo = $extIcons[$ext] ?? ['label' => strtoupper($ext), 'color' => '#6b7280'];

    // Rute kembali sesuai kategori dokumen, dipakai kalau user klik "Batal"
    $categoryRoutes = [
        'policy'         => 'documents.policy',
        'roles'          => 'documents.roles',
        'knowledge-base' => 'documents.knowledge-base',
    ];
    $backRoute = route($categoryRoutes[$document->category] ?? 'dashboard');
@endphp

@section('content')

    {{-- ===================================================================
         MODAL PERINGATAN KERAHASIAAN DOKUMEN — blocking, wajib di-OK dulu
         =================================================================== --}}
    <div id="confidentiality-modal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">
                    Peringatan Kerahasiaan Dokumen
                </h3>

                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        Dokumen ini merupakan milik Perusahaan dan diklasifikasikan sebagai
                        <strong class="text-gray-800 dark:text-gray-100">RAHASIA</strong>.
                        Akses hanya diberikan kepada karyawan yang berwenang dan semata-mata
                        untuk kepentingan pelaksanaan pekerjaan.
                    </p>

                    <button type="button" id="detail-toggle-btn"
                            class="flex items-center gap-1.5 text-primary-700 text-xs font-semibold hover:underline">
                        <span id="detail-toggle-label">Lihat Ketentuan Lengkap</span>
                        <svg id="detail-toggle-chevron" class="w-3.5 h-3.5 transition-transform" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="detail-text" class="hidden space-y-3 pt-1">
                        <p>
                            Dilarang memotret, melakukan tangkapan layar (<strong>screenshot</strong>),
                            merekam layar, menyalin, mencetak, mengunduh, menggandakan, meneruskan,
                            mengunggah, mengungkapkan, atau menyebarluaskan seluruh maupun sebagian isi
                            dokumen kepada pihak yang tidak berwenang, baik di dalam maupun di luar
                            Perusahaan, melalui media apa pun, kecuali atas persetujuan tertulis dari
                            pejabat yang berwenang.
                        </p>
                        <p>
                            Dengan memilih <strong class="text-gray-800 dark:text-gray-100">"Saya Memahami dan Menyetujui"</strong>,
                            saya menyatakan telah memahami dan bersedia mematuhi kewajiban kerahasiaan
                            dokumen ini. Setiap pelanggaran dapat dikenakan tindakan disiplin sesuai
                            Peraturan Perusahaan/PKB, perjanjian kerja atau NDA, kebijakan keamanan
                            informasi, serta ketentuan peraturan perundang-undangan yang berlaku.
                        </p>
                    </div>
                </div>

                <label class="flex items-start gap-2.5 mt-5 cursor-pointer select-none">
                    <input type="checkbox" id="confidentiality-checkbox"
                           class="mt-0.5 w-4 h-4 rounded border-gray-300 text-primary-700 focus:ring-primary-700 shrink-0">
                    <span class="text-sm text-gray-700 dark:text-gray-200">
                        Saya telah membaca, memahami, dan menyetujui ketentuan kerahasiaan dokumen ini.
                    </span>
                </label>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 rounded-b-2xl flex justify-end gap-3">
                <button type="button" id="modal-batal-btn"
                        class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                    Batal
                </button>
                <button type="button" id="modal-buka-btn" disabled
                        class="bg-primary-700 text-white font-semibold px-5 py-2.5 rounded-lg transition text-sm opacity-40 cursor-not-allowed">
                    Buka Dokumen
                </button>
            </div>
        </div>
    </div>

    {{-- ===================================================================
         KONTEN DOKUMEN — disembunyikan sampai modal di-OK
         =================================================================== --}}
    <div id="document-content" class="hidden">

        {{-- Header --}}
        <div class="mb-4 flex items-center justify-between">
            <div>
                <a href="javascript:history.back()"
                   class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
                    ← Kembali
                </a>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $document->title }}</h2>
                <p class="text-gray-400 text-xs mt-0.5 capitalize">
                    {{ $document->category }} &mdash; diupload {{ $document->created_at->format('d M Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm px-3 py-1 rounded-full font-medium
                    {{ $document->isReadBy(Auth::user()) ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-500' }}">
                    {{ $document->isReadBy(Auth::user()) ? '✓ Sudah Dibaca' : '● Belum Dibaca' }}
                </span>
                @if($document->requires_acknowledgement)
                    @if($document->isAcknowledgedBy(Auth::user()))
                        <span class="text-sm px-3 py-1 rounded-full font-medium bg-blue-50 text-blue-600">✓ Sudah Acknowledge</span>
                    @else
                        <span class="text-sm px-3 py-1 rounded-full font-medium bg-red-50 text-red-500">⚠ Perlu Acknowledge</span>
                    @endif
                @endif
            </div>
        </div>

        {{-- Viewer Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded text-white text-xs font-bold"
                          style="background: {{ $fileInfo['color'] }};">
                        {{ $fileInfo['label'] }}
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $document->title }}</span>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Bookmark --}}
                    <form method="POST" action="{{ route('bookmarks.toggle', $document->id) }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition
                                    {{ $document->isBookmarkedBy(Auth::user()) ? 'bg-primary-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-primary-50 hover:text-primary-700' }}">
                            <svg class="w-3.5 h-3.5" fill="{{ $document->isBookmarkedBy(Auth::user()) ? 'currentColor' : 'none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            {{ $document->isBookmarkedBy(Auth::user()) ? 'Tersimpan' : 'Simpan' }}
                        </button>
                    </form>
                    <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">Read Only</span>
                </div>
            </div>

            {{-- Viewer Area --}}
            <div class="relative" style="min-height: 60vh;">

                @if($isPdf)
                    {{-- Watermark overlay --}}
                    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden select-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="absolute w-full text-center"
                                 style="top: {{ 10 + ($i * 16) }}%; transform: rotate(-30deg); opacity: 0.07;">
                                <span class="text-gray-800 font-bold text-xl tracking-widest whitespace-nowrap">
                                    {{ $watermark }} &nbsp;&nbsp;&nbsp; {{ $watermark }}
                                </span>
                            </div>
                        @endfor
                    </div>
                    
                    {{-- Container PDF — dirender via PDF.js (canvas) setelah user klik "Buka Dokumen", TANPA toolbar bawaan browser --}}
                    <div id="document-frame-container" data-pdf-url="{{ route('documents.pdf', $document->id) }}"
                         style="max-height: 85vh; overflow-y: auto;"></div>

                @elseif($isImage)
                    {{-- Watermark overlay --}}
                    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden select-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="absolute w-full text-center"
                                 style="top: {{ 10 + ($i * 16) }}%; transform: rotate(-30deg); opacity: 0.12;">
                                <span class="text-gray-800 font-bold text-lg tracking-widest whitespace-nowrap">
                                    {{ $watermark }} &nbsp;&nbsp;&nbsp; {{ $watermark }}
                                </span>
                            </div>
                        @endfor
                    </div>
                     {{-- Container gambar — img baru dibuat via JS setelah user klik "Buka Dokumen" --}}
                    <div id="document-frame-container" data-pdf-url="{{ route('documents.pdf', $document->id) }}"
                         class="flex items-center justify-center p-6" style="min-height: 60vh;"></div>

                               @elseif($ext === 'docx')
                    {{-- Watermark overlay --}}
                    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden select-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="absolute w-full text-center"
                                 style="top: {{ 10 + ($i * 16) }}%; transform: rotate(-30deg); opacity: 0.06;">
                                <span class="text-gray-800 font-bold text-xl tracking-widest whitespace-nowrap">
                                    {{ $watermark }} &nbsp;&nbsp;&nbsp; {{ $watermark }}
                                </span>
                            </div>
                        @endfor
                    </div>
                    <div id="office-preview-container" data-file-url="{{ route('documents.pdf', $document->id) }}"
                         data-file-ext="docx" class="p-8 overflow-auto bg-white" style="max-height: 85vh;"></div>

                @elseif(in_array($ext, ['xlsx', 'xls']))
                    {{-- Watermark overlay --}}
                    <div class="absolute inset-0 pointer-events-none z-10 overflow-hidden select-none">
                        @for($i = 0; $i < 6; $i++)
                            <div class="absolute w-full text-center"
                                 style="top: {{ 10 + ($i * 16) }}%; transform: rotate(-30deg); opacity: 0.06;">
                                <span class="text-gray-800 font-bold text-xl tracking-widest whitespace-nowrap">
                                    {{ $watermark }} &nbsp;&nbsp;&nbsp; {{ $watermark }}
                                </span>
                            </div>
                        @endfor
                    </div>
                    <div id="office-preview-container" data-file-url="{{ route('documents.pdf', $document->id) }}"
                         data-file-ext="{{ $ext }}" class="p-6 overflow-auto bg-white" style="max-height: 85vh;"></div>

               @elseif($isOffice)
        <div class="flex flex-col items-center justify-center py-16 px-6" style="min-height: 60vh;">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-2xl font-bold mb-5 shadow-sm"
                 style="background: {{ $fileInfo['color'] }};">
                {{ $fileInfo['label'] }}
            </div>
            <h3 class="text-gray-800 dark:text-gray-100 font-semibold text-base mb-2">{{ $document->title }}</h3>
            <p class="text-gray-400 text-sm text-center max-w-sm mb-6">
                File <strong>{{ strtoupper($ext) }}</strong> tidak dapat ditampilkan langsung di browser.
                Hubungi Admin untuk informasi lebih lanjut.
            </p>
            @if(Auth::user()->isAdmin() || Auth::user()->isSuperUser())
                <a href="{{ route('documents.download', $document->id) }}"
                   class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-3 rounded-xl transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download {{ strtoupper($ext) }}
                </a>
            @endif
            <p class="text-xs text-gray-300 dark:text-gray-600 mt-4">
                {{ $watermark }} — dokumen bersifat rahasia
            </p>
        </div>

                @else
                    {{-- Fallback --}}
                    <div class="flex flex-col items-center justify-center py-16">
                        <p class="text-gray-400 text-sm">Format file tidak didukung untuk preview.</p>
                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperUser())
                        <a href="{{ route('documents.download', $document->id) }}"
                        class="mt-4 inline-flex items-center gap-2 bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                            Download File
                        </a>
                    @endif
                    </div>
                @endif

            </div>
        </div>

        {{-- Acknowledge Banner --}}
        @if($document->requires_acknowledgement && !$document->isAcknowledgedBy(Auth::user()))
            <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-800 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300 text-sm">Dokumen ini memerlukan Acknowledgement</p>
                        <p class="text-yellow-700 dark:text-yellow-400 text-xs mt-1">
                            Dengan menekan tombol di bawah, Anda menyatakan telah membaca dan memahami isi dokumen ini.
                        </p>
                        <form method="POST" action="{{ route('documents.acknowledge', $document->id) }}" class="mt-3">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Anda menyatakan telah membaca dan memahami dokumen ini. Lanjutkan?')"
                                    class="inline-flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Saya Sudah Membaca & Memahami
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($document->requires_acknowledgement && $document->isAcknowledgedBy(Auth::user()))
            <div class="mt-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-300">Sudah Acknowledge</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-0.5">
                        Anda telah menyatakan membaca & memahami dokumen ini pada
                        {{ Auth::user()->documentReads()->where('document_id', $document->id)->first()?->acknowledged_at?->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        @endif

        <p class="text-center text-gray-300 text-xs mt-3">
            Dokumen ini bersifat rahasia. Dilarang mendistribusikan atau mereproduksi tanpa izin.
        </p>

    </div>

@endsection

@push('scripts')

@if($isPdf)
<script type="module">
    import * as pdfjsLib from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.mjs';
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.worker.min.mjs';

    window.renderPdfWithPdfJs = async function (url, container) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-20">
                <svg class="animate-spin w-8 h-8 text-primary-700 mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-gray-400">Memuat dokumen...</p>
            </div>`;

        try {
            const pdf = await pdfjsLib.getDocument(url).promise;

            container.innerHTML = '';
            container.style.background = '#525659';
            container.style.padding = '16px 0';

            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const page = await pdf.getPage(pageNum);
                const containerWidth = container.clientWidth || 800;
                const unscaledViewport = page.getViewport({ scale: 1 });
                const scale = (containerWidth - 32) / unscaledViewport.width;
                const viewport = page.getViewport({ scale: scale });

                const canvas = document.createElement('canvas');
                canvas.className = 'mx-auto block shadow-lg mb-4 bg-white';
                canvas.oncontextmenu = () => false;
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                await page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise;
                container.appendChild(canvas);
            }
        } catch (err) {
            console.error('PDF.js render error:', err);
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <p class="text-red-500 text-sm">Gagal memuat pratinjau PDF. Silakan hubungi Admin.</p>
                </div>`;
        }
    };
</script>
@endif

@if($ext === 'docx')
<script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.7/dist/docx-preview.min.js"></script>
@elseif(in_array($ext, ['xlsx', 'xls']))
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
@endif

<script>
    window.renderOfficePreview = async function (url, container, ext) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-20">
                <svg class="animate-spin w-8 h-8 text-primary-700 mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-gray-400">Memuat pratinjau dokumen...</p>
            </div>`;

        try {
            const response    = await fetch(url);
            const arrayBuffer = await response.arrayBuffer();

                        if (ext === 'docx') {
                container.innerHTML = '';
                await docx.renderAsync(arrayBuffer, container, container, {
                    inWrapper: true,
                    ignoreWidth: false,
                    ignoreHeight: false,
                });

                // Cek apakah ada gambar yang gagal di-load (EMF/WMF tidak didukung browser)
                setTimeout(function () {
                    const imgs = container.querySelectorAll('img');
                    let broken = 0;
                    imgs.forEach(function (img) {
                        if (img.complete && img.naturalWidth === 0) broken++;
                    });

                    const textLength = (container.innerText || '').trim().length;
                    const htmlLength = container.innerHTML.length;

                    // Kosong visual: tidak ada gambar & teks sangat sedikit padahal DOM besar
                    // (ciri khas dokumen berisi SmartArt/shape yang tidak didukung docx-preview)
                    const visuallyEmpty = imgs.length === 0 && textLength < 150 && htmlLength > 2000;

                    if (broken > 0 || visuallyEmpty) {
                        container.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-300 text-sm font-semibold mb-1">
                                    Dokumen tidak dapat ditampilkan
                                </p>
                                <p class="text-gray-400 text-xs max-w-sm leading-relaxed">
                                    Dokumen ini berisi diagram atau gambar yang tidak didukung untuk pratinjau di browser.
                                    Silakan hubungi Admin untuk meminta versi PDF dari dokumen ini.
                                </p>
                            </div>`;
                    }
                }, 800);

            } else if (ext === 'xlsx' || ext === 'xls') {
                const workbook = XLSX.read(arrayBuffer, { type: 'array' });
                container.innerHTML = '';

                if (workbook.SheetNames.length > 1) {
                    const tabs = document.createElement('div');
                    tabs.className = 'flex gap-1 mb-4 border-b border-gray-200 flex-wrap';
                    workbook.SheetNames.forEach((name, idx) => {
                        const tab = document.createElement('button');
                        tab.type = 'button';
                        tab.textContent = name;
                        tab.className = idx === 0
                            ? 'px-3 py-1.5 text-xs font-semibold rounded-t-lg bg-primary-700 text-white'
                            : 'px-3 py-1.5 text-xs font-semibold rounded-t-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
                        tab.onclick = () => {
                            container.querySelectorAll('.xlsx-sheet-content').forEach(el => el.classList.add('hidden'));
                            document.getElementById('xlsx-sheet-' + idx).classList.remove('hidden');
                            tabs.querySelectorAll('button').forEach(b => {
                                b.className = 'px-3 py-1.5 text-xs font-semibold rounded-t-lg bg-gray-100 text-gray-600 hover:bg-gray-200';
                            });
                            tab.className = 'px-3 py-1.5 text-xs font-semibold rounded-t-lg bg-primary-700 text-white';
                        };
                        tabs.appendChild(tab);
                    });
                    container.appendChild(tabs);
                }

                workbook.SheetNames.forEach((name, idx) => {
                    const sheetDiv = document.createElement('div');
                    sheetDiv.id = 'xlsx-sheet-' + idx;
                    sheetDiv.className = 'xlsx-sheet-content text-xs' + (idx === 0 ? '' : ' hidden');
                    sheetDiv.innerHTML = XLSX.utils.sheet_to_html(workbook.Sheets[name]);
                    container.appendChild(sheetDiv);
                });

                container.querySelectorAll('table').forEach(t => {
                    t.classList.add('border-collapse', 'w-full');
                    t.querySelectorAll('td, th').forEach(cell => {
                        cell.classList.add('border', 'border-gray-200', 'px-2', 'py-1');
                    });
                });
            }
        } catch (err) {
            console.error('Office preview render error:', err);
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <p class="text-red-500 text-sm">Gagal memuat pratinjau dokumen. Silakan hubungi Admin.</p>
                </div>`;
        }
    };

    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && ['p','s','u'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            return false;
        }
    });

    // ===== Modal Peringatan Kerahasiaan =====
    (function () {
        const checkbox   = document.getElementById('confidentiality-checkbox');
        const bukaBtn    = document.getElementById('modal-buka-btn');
        const batalBtn   = document.getElementById('modal-batal-btn');
        const modal      = document.getElementById('confidentiality-modal');
        const content    = document.getElementById('document-content');
        const detailBtn  = document.getElementById('detail-toggle-btn');
        const detailText = document.getElementById('detail-text');
        const chevron    = document.getElementById('detail-toggle-chevron');
        const detailLabel= document.getElementById('detail-toggle-label');

        // Toggle expand/collapse ketentuan lengkap
        detailBtn.addEventListener('click', function () {
            const isHidden = detailText.classList.toggle('hidden');
            chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
            detailLabel.textContent = isHidden ? 'Lihat Ketentuan Lengkap' : 'Sembunyikan Ketentuan';
        });

        // Enable/disable tombol "Buka Dokumen" sesuai checkbox
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                bukaBtn.disabled = false;
                bukaBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                bukaBtn.classList.add('hover:bg-primary-800');
            } else {
                bukaBtn.disabled = true;
                bukaBtn.classList.add('opacity-40', 'cursor-not-allowed');
                bukaBtn.classList.remove('hover:bg-primary-800');
            }
        });

                // Klik "Buka Dokumen" — tutup modal, tampilkan konten, baru buat elemen file (iframe/img) dari nol
        bukaBtn.addEventListener('click', function () {
            if (bukaBtn.disabled) return;

            modal.remove();
            content.classList.remove('hidden');

            const container = document.getElementById('document-frame-container');
            if (container && container.dataset.pdfUrl) {
                const isPdfType = @json($isPdf);

                if (isPdfType) {
                    if (window.renderPdfWithPdfJs) {
                        window.renderPdfWithPdfJs(container.dataset.pdfUrl, container);
                    }
            } else {
                    const img = document.createElement('img');
                    img.src = container.dataset.pdfUrl;
                    img.alt = @json($document->title);
                    img.className = 'max-w-full max-h-screen object-contain rounded-lg shadow-sm';
                    img.oncontextmenu = () => false;
                    container.appendChild(img);
                }
            }

            const officeContainer = document.getElementById('office-preview-container');
            if (officeContainer && officeContainer.dataset.fileUrl && window.renderOfficePreview) {
                window.renderOfficePreview(officeContainer.dataset.fileUrl, officeContainer, officeContainer.dataset.fileExt);
            }
        });

        // Klik "Batal" — dokumen tidak dibuka, kembali ke halaman list kategori
        batalBtn.addEventListener('click', function () {
            window.location.href = "{{ $backRoute }}";
        });

        // Cegah ESC menutup modal (tidak ada listener untuk close, jadi otomatis aman)
        // Cegah klik di luar modal menutup (tidak ada listener backdrop-click, aman by design)
    })();
</script>
@endpush