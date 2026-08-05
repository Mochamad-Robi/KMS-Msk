@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Profil Saya</h2>
        <p class="text-gray-400 text-sm mt-0.5">Lihat dan perbarui informasi akun kamu</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

        {{-- Cover / Avatar --}}
        <div class="bg-gradient-to-r from-primary-700 to-primary-900 h-28 relative">
            <div class="absolute -bottom-10 left-6">
                @if($user->avatar)
                    <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($user->avatar)]) }}"
                    class="w-20 h-20 rounded-2xl border-4 border-white object-cover object-top shadow-lg"/>
                @else
                    <div class="w-20 h-20 rounded-2xl border-4 border-white bg-primary-700 flex items-center justify-center shadow-lg">
                        <span class="text-white text-2xl font-black">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-14 px-6 pb-6">

            {{-- User Info --}}
            <div class="mb-6">
                <p class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ $user->name }}</p>
                <p class="text-gray-400 text-sm">{{ $user->employee_id }} — {{ $user->role?->name }}</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg px-4 py-3 mb-5 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg px-4 py-3 mb-5 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">

                    {{-- Upload Avatar — hanya admin/super-user --}}
                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperUser())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Foto Profil <span class="text-gray-400 font-normal">(JPG/PNG maks 5MB)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center shrink-0" id="avatar-preview-wrapper">
                                    @if($user->avatar)
                                        <img id="avatar-preview"
                                             src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($user->avatar)]) }}"
                                             class="w-full h-full object-cover object-top"/>
                                    @else
                                        <span class="text-primary-700 font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <label for="avatar"
                                           class="cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition inline-block">
                                        Pilih Foto
                                    </label>
                                    <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png" class="hidden"
                                           onchange="previewAvatar(this)"/>
                                    <p class="text-xs text-gray-400 mt-1" id="avatar-filename">Belum ada file dipilih</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Info untuk user biasa --}}
                        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-blue-600 dark:text-blue-400">Foto profil dikelola oleh Admin. Hubungi HRD untuk perubahan foto.</p>
                        </div>
                    @endif

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" disabled
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>

                    {{-- Email (readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full border border-gray-100 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed"/>
                        <p class="text-xs text-gray-400 mt-1">Email tidak bisa diubah sendiri, hubungi Admin.</p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Handphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                    </div>

                    {{-- Password --}}
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Ganti Password</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Password Baru <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input type="password" name="password"
                                       placeholder="Minimal 8 karakter"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                       placeholder="Ulangi password"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
                            </div>
                        </div>
                    </div>

                    {{-- Info readonly --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Departemen</p>
                            <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->department?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Jabatan</p>
                            <p class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->position?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">ID Karyawan</p>
                            <p class="font-semibold text-gray-700 dark:text-gray-200 font-mono">{{ $user->employee_id }}</p>
                        </div>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}"
                       class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrapper = document.getElementById('avatar-preview-wrapper');
            wrapper.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain" rounded-xl"/>`;
        };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('avatar-filename').textContent = input.files[0].name;
    }
}
</script>
@endpush