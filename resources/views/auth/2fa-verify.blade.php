@extends('layouts.auth')
@section('title', 'Verifikasi 2FA')

@section('content')

    <div class="text-center mb-6">
        <div class="text-4xl mb-3">🔐</div>
        <h2 class="text-xl font-bold text-gray-800">Verifikasi 2FA</h2>
        <p class="text-gray-500 text-sm mt-1">
            Masukkan kode 6 digit dari aplikasi Google Authenticator
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode OTP
            </label>
            <input
                type="text"
                name="otp"
                maxlength="6"
                placeholder="000000"
                autofocus
                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center text-2xl font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-primary-700 @error('otp') border-red-400 @enderror"
            />
        </div>

        <button
            type="submit"
            class="w-full bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2.5 rounded-lg transition duration-200 text-sm"
        >
            Verifikasi
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-4">
        <a href="{{ route('login') }}" class="text-primary-700 hover:underline">
            ← Kembali ke Login
        </a>
    </p>

@endsection