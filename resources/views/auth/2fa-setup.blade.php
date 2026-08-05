@extends('layouts.auth')
@section('title', 'Setup 2FA')

@section('content')

    <h2 class="text-xl font-bold text-gray-800 mb-1">Aktifkan 2FA</h2>
    <p class="text-gray-500 text-sm mb-6">
        Scan QR code berikut menggunakan aplikasi Google Authenticator
    </p>

    {{-- QR Code --}}
    <div class="flex justify-center mb-4">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrUrl) }}"
             alt="QR Code 2FA"
             class="rounded-lg border border-gray-200 p-2"
        />
    </div>

    {{-- Manual key --}}
    <div class="bg-gray-50 rounded-lg p-3 mb-6 text-center">
        <p class="text-xs text-gray-500 mb-1">Atau masukkan kode manual:</p>
        <code class="text-sm font-mono font-bold text-primary-700 tracking-widest">
            {{ $secret }}
        </code>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.enable') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode OTP (konfirmasi)
            </label>
            <input
                type="text"
                name="otp"
                maxlength="6"
                placeholder="000000"
                autofocus
                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-2xl font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-primary-700"
            />
        </div>

        <button
            type="submit"
            class="w-full bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2.5 rounded-lg transition duration-200 text-sm"
        >
            Aktifkan 2FA
        </button>
    </form>

@endsection