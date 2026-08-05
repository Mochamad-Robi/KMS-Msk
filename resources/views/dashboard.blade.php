<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PT MSK DMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-800">Selamat datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-500 mt-2">PT MSK Document Management System</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm">Logout</button>
        </form>
    </div>
</body>
</html>