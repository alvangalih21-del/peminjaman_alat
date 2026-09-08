<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Peminjaman Alat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">
    <header class="sticky top-0 z-20 border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center gap-5 px-4 py-4 lg:px-8">
            <a href="{{ route('peminjam.dashboard') }}" class="shrink-0 text-xl font-black tracking-tight text-gray-900">Peminjam</a>
            <form action="{{ route('peminjam.katalog') }}" method="GET" class="flex min-w-0 flex-1">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari alat yang Anda butuhkan..." class="min-w-0 flex-1 rounded-l-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                <button class="rounded-r-lg bg-gray-900 px-5 text-sm font-semibold text-white hover:bg-gray-700">Cari</button>
            </form>
            <nav class="hidden items-center gap-4 text-sm font-semibold md:flex">
                <a href="{{ route('peminjam.dashboard') }}" class="hover:text-emerald-600">Beranda</a>
                <a href="{{ route('peminjam.katalog') }}" class="hover:text-emerald-600">Katalog</a>
                <a href="{{ route('peminjam.riwayat') }}" class="hover:text-emerald-600">Riwayat</a>
                <a href="{{ route('profile.show') }}" class="hover:text-emerald-600">Profil</a>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">Logout</button>
            </form>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-6 lg:px-8">@yield('content')</main>
</body>
</html>