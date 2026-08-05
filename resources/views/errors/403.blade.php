<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="text-center">
        <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-5a4 4 0 100-8 4 4 0 000 8zm-7 9a9 9 0 1118 0H3z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-primary-800 mb-2">403</h1>
        <p class="text-gray-600 font-medium mb-1">Akses Ditolak</p>
        <p class="text-gray-400 text-sm mb-6">{{ $exception->getMessage() ?? 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        <a href="{{ url()->previous() }}"
           class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
            ← Kembali
        </a>
    </div>
</body>
</html>