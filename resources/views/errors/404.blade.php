<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="text-center">
        <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-primary-800 mb-2">404</h1>
        <p class="text-gray-600 font-medium mb-1">Halaman Tidak Ditemukan</p>
        <p class="text-gray-400 text-sm mb-6">Halaman yang kamu cari tidak ada atau sudah dipindahkan.</p>
        <a href="{{ route('dashboard') }}"
           class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
            ← Kembali ke Dashboard
        </a>
    </div>
</body>
</html>