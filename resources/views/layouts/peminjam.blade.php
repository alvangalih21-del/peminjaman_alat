<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Peminjaman Alat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_28%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)]">
        <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-sm">
            <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 lg:px-8">
                <a href="{{ route('peminjam.dashboard') }}" class="shrink-0 text-xl font-black tracking-tight text-slate-900">
                    <span class="text-emerald-600">Peminjam</span>
                </a>

                <form action="{{ route('peminjam.katalog') }}" method="GET" class="flex min-w-0 flex-1 items-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari alat yang Anda butuhkan..." class="min-w-0 flex-1 border-0 bg-transparent px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 outline-none">
                    <button class="bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">Cari</button>
                </form>

                <nav class="hidden items-center gap-2 text-sm font-semibold md:flex">
                    <a href="{{ route('peminjam.dashboard') }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs('peminjam.dashboard') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Beranda</a>
                    <a href="{{ route('peminjam.katalog') }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs('peminjam.katalog') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Katalog</a>
                    <a href="{{ route('peminjam.riwayat') }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs('peminjam.riwayat') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Riwayat</a>
                    <a href="{{ route('profile.show') }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs('profile.show') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Profil</a>
                </nav>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:border-emerald-300 hover:text-emerald-700">Logout</button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 lg:px-8">@yield('content')</main>
    </div>
</body>
</html>