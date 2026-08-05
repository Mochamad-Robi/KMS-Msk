@extends('layouts.app')
@section('title', 'Tambah Karyawan')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-gray-400 hover:text-primary-700 flex items-center gap-1 mb-2">
            ← Kembali
        </a>
        <h2 class="text-lg font-bold text-gray-800">Tambah Karyawan Baru</h2>
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

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Avatar Upload --}}
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Foto Profil <span class="text-gray-400 font-normal">(opsional)</span>
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center shrink-0" id="avatar-preview-wrapper">
                        <svg class="w-8 h-8 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <label for="avatar"
                            class="cursor-pointer bg-white dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 border border-gray-300 dark:border-gray-500 text-gray-600 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition inline-block">
                            Pilih Foto
                        </label>
                        <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png" class="hidden"
                            onchange="previewAvatar(this)"/>
                        <p class="text-xs text-gray-400 mt-1" id="avatar-filename">JPG/PNG maks 5MB</p>
                    </div>
                </div>
            </div>

            <div class="space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Karyawan</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                               placeholder="MSK-001"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama lengkap"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@ptmsk.co.id"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                               placeholder="Minimal 8 karakter"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               placeholder="Ulangi password"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                    <select name="grade_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Grade --</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                        <select name="department_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <select name="position_id" id="position_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Simpan Karyawan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                    Batal
                </a>
            </div>

        </form>
    </div>

    {{-- Modal Crop Foto --}}
    <div id="crop-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 px-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-5">
            <h3 class="font-bold text-gray-800 text-base mb-4">Atur Posisi Foto</h3>
            <div class="w-full h-72 bg-gray-100 rounded-lg overflow-hidden">
                <img id="crop-image" src="" class="max-w-full"/>
            </div>
            <p class="text-xs text-gray-400 mt-2">Drag untuk geser, scroll untuk zoom</p>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="saveCroppedImage()"
                        class="flex-1 bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Simpan Crop
                </button>
                <button type="button" onclick="closeCropModal()"
                        class="border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
const positionsByDept = @json($positions->groupBy('department_id'));

const deptSelect  = document.querySelector('select[name="department_id"]');
const posSelect   = document.querySelector('select[name="position_id"]');
const selectedPos = "{{ old('position_id', '') }}";

let cropper = null;

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('crop-image').src = e.target.result;
            document.getElementById('crop-modal').classList.remove('hidden');
            document.getElementById('crop-modal').classList.add('flex');

            if (cropper) cropper.destroy();
            cropper = new Cropper(document.getElementById('crop-image'), {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxResizable: false,
                cropBoxMovable: false,
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// FIX: tambahkan parameter resetInput.
// Sebelumnya closeCropModal() SELALU mengosongkan input #avatar,
// padahal saveCroppedImage() juga memanggil closeCropModal() setelah
// memasang file hasil crop ke input tsb -> file hasil crop langsung
// terhapus lagi sebelum sempat di-submit form.
function closeCropModal(resetInput = true) {
    document.getElementById('crop-modal').classList.add('hidden');
    document.getElementById('crop-modal').classList.remove('flex');
    if (resetInput) {
        document.getElementById('avatar').value = '';
    }
}

function saveCroppedImage() {
    // pakai canvas manual supaya bisa kasih background putih dulu
    // sebelum di-export ke JPEG (area transparan PNG kalau langsung
    // di-export ke JPEG defaultnya jadi hitam)
    const sourceCanvas = cropper.getCroppedCanvas({ width: 400, height: 400 });

    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = sourceCanvas.width;
    finalCanvas.height = sourceCanvas.height;
    const ctx = finalCanvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, finalCanvas.width, finalCanvas.height);
    ctx.drawImage(sourceCanvas, 0, 0);

    finalCanvas.toBlob(function (blob) {
        const wrapper = document.getElementById('avatar-preview-wrapper');
        const url = URL.createObjectURL(blob);
        wrapper.innerHTML = `<img src="${url}" class="w-full h-full object-cover rounded-xl"/>`;
        document.getElementById('avatar-filename').textContent = 'Foto sudah di-crop ✓';

        const newFile = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(newFile);
        document.getElementById('avatar').files = dataTransfer.files;

        // FIX: jangan reset input avatar di sini, file hasil crop harus tetap nempel
        closeCropModal(false);
    }, 'image/jpeg', 0.9);
}

function filterPositions(deptId) {
    const current = posSelect.value;
    posSelect.innerHTML = '<option value="">-- Pilih Jabatan --</option>';

    if (!deptId || !positionsByDept[deptId]) return;

    positionsByDept[deptId].forEach(pos => {
        const opt = document.createElement('option');
        opt.value = pos.id;
        opt.textContent = pos.name;
        if (String(pos.id) === String(selectedPos) || String(pos.id) === String(current)) {
            opt.selected = true;
        }
        posSelect.appendChild(opt);
    });
}

filterPositions(deptSelect.value);

deptSelect.addEventListener('change', function () {
    filterPositions(this.value);
});
</script>
@endpush

@endsection