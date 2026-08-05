@extends('layouts.app')
@section('title', 'User Management')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">User Management</h2>
            <p class="text-gray-400 text-sm mt-0.5">Total {{ $users->total() }} karyawan</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Tambah Karyawan
        </a>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-5">
        <div class="flex gap-2">
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nama, email, atau ID karyawan..."
                       class="w-full pl-9 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-700"/>
            </div>
            <button type="submit"
                    class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition">
                    Reset
                </a>
            @endif
        </div>
        @if($search)
            <p class="text-xs text-gray-400 mt-2">Hasil pencarian: <strong>{{ $search }}</strong></p>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Karyawan</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">ID</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Departemen</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Role</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Grade</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Login</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Gift HUT</th>
                    <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">

                        {{-- Karyawan --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ route('media.serve', ['type' => 'avatars', 'filename' => basename($user->avatar)]) }}"
                                         alt="{{ $user->name }}"
                                         class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                @else
                                    <div class="w-10 h-10 bg-primary-700 rounded-full flex items-center justify-center shrink-0">
                                        <span class="text-white text-xs font-bold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- ID --}}
                        <td class="px-5 py-3 text-gray-500 font-mono text-xs">
                            {{ $user->employee_id }}
                        </td>

                        {{-- Departemen --}}
                        <td class="px-5 py-3 text-gray-500">
                            {{ $user->department?->name ?? '-' }}
                        </td>

                        {{-- Role --}}
                        <td class="px-5 py-3">
                            <span class="bg-primary-50 text-primary-700 text-xs px-2 py-1 rounded-full">
                                {{ $user->role?->name ?? '-' }}
                            </span>
                        </td>

                        {{-- Grade --}}
                        <td class="px-5 py-3 text-gray-500">
                            {{ $user->grade?->label() ?? '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3">
                            @if($user->is_active)
                                <span class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="bg-gray-100 text-gray-400 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                            @endif
                        </td>

                        {{-- Login Count --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">{{ $user->login_count ?? 0 }}x</span>
                            </div>
                            @if($user->last_login_at)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $user->last_login_at->diffForHumans() }}</p>
                            @else
                                <p class="text-xs text-gray-400 mt-0.5">Belum pernah</p>
                            @endif
                        </td>

                        {{-- Gift HUT --}}
                        <td class="px-5 py-3">
                            @if(isset($birthdayGifts[$user->id]))
                                @if($birthdayGifts[$user->id]->is_claimed)
                                    <span class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-full">✓ Diklaim</span>
                                @else
                                    <div class="flex flex-col gap-1">
                                        <span class="bg-yellow-50 text-yellow-700 text-xs px-2 py-1 rounded-full font-mono">
                                            {{ $birthdayGifts[$user->id]->gift_code }}
                                        </span>
                                        <button
                                            onclick="openGiftModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $birthdayGifts[$user->id]->gift_code }}', '{{ addslashes($birthdayGifts[$user->id]->message ?? '') }}')"
                                            class="text-xs text-blue-500 hover:underline text-left">
                                            Edit Gift
                                        </button>
                                    </div>
                                @endif
                            @else
                                <button
                                    onclick="openGiftModal({{ $user->id }}, '{{ addslashes($user->name) }}', '', '')"
                                    class="text-xs text-primary-700 hover:underline font-medium">
                                    + Set Gift
                                </button>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-xs text-blue-500 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-orange-500 hover:underline">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                          onsubmit="return confirm('Hapus karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                            Belum ada karyawan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal Set Birthday Gift --}}
    <div id="gift-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/40" onclick="closeGiftModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Set Gift Ulang Tahun</h3>
                    <p class="text-xs text-gray-400 mt-0.5" id="gift-modal-subtitle">untuk —</p>
                </div>
                <button onclick="closeGiftModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- _method PATCH sebagai hidden input statis, bukan @method() Blade --}}
            <form method="POST" id="gift-form" action="">
                @csrf
                <input type="hidden" name="_method" value="PATCH">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Gift <span class="text-gray-400 font-normal">(voucher, kode promo, dll)</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="gift_code" id="gift-code-input"
                                   placeholder="Contoh: GIFT-HUT-2024"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700 font-mono uppercase"/>
                            <button type="button" onclick="generateCode()"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-3 py-2 rounded-lg transition whitespace-nowrap">
                                Auto Generate
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pesan Ulang Tahun <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="message" id="gift-message-input" rows="3"
                                  placeholder="Contoh: Selamat ulang tahun! Semoga sukses selalu 🎉"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-700 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-5">
                    <button type="submit"
                            class="flex-1 bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Simpan Gift
                    </button>
                    <button type="button" onclick="closeGiftModal()"
                            class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold px-5 py-2.5 rounded-lg transition text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function openGiftModal(userId, userName, existingCode, existingMessage) {
    document.getElementById('gift-modal-subtitle').textContent = 'untuk ' + userName;
    document.getElementById('gift-form').action = '/admin/users/' + userId + '/birthday-message';
    document.getElementById('gift-code-input').value = existingCode;
    document.getElementById('gift-message-input').value = existingMessage;

    const modal = document.getElementById('gift-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeGiftModal() {
    const modal = document.getElementById('gift-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = 'GIFT-';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('gift-code-input').value = code;
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGiftModal();
});
</script>
@endpush