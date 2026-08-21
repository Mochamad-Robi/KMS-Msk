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
                    
                    {{-- Container PDF — iframe baru dibuat via JS setelah user klik "Buka Dokumen" --}}
                    <div id="document-frame-container" data-pdf-url="{{ route('documents.pdf', $document->id) }}" style="height: 80vh;"></div>

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
<script>
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
                    const iframe = document.createElement('iframe');
                    iframe.src = container.dataset.pdfUrl;
                    iframe.className = 'w-full h-full border-0';
                    container.appendChild(iframe);
                } else {
                    const img = document.createElement('img');
                    img.src = container.dataset.pdfUrl;
                    img.alt = @json($document->title);
                    img.className = 'max-w-full max-h-screen object-contain rounded-lg shadow-sm';
                    img.oncontextmenu = () => false;
                    container.appendChild(img);
                }
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