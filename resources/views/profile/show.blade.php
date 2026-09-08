@extends(auth()->user()->role === 'peminjam' ? 'layouts.peminjam' : 'layouts.app')

@section('title', 'Profil Saya')
@section('header-title', 'Profil Saya')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col items-center gap-5 sm:flex-row">
                @if(auth()->user()->foto_profile)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profile) }}" alt="Foto {{ auth()->user()->name }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-emerald-100">
                @else
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-3xl font-black text-emerald-700 ring-4 ring-emerald-50">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div class="text-center sm:text-left">
                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Detail Profil</p>
                    <h1 class="mt-1 text-2xl font-black text-gray-900">{{ auth()->user()->name }}</h1>
                    <span class="mt-2 inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-bold uppercase text-gray-600">{{ auth()->user()->role }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <dl class="divide-y divide-gray-100">
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3"><dt class="text-sm font-semibold text-gray-500">Nama Lengkap</dt><dd class="text-sm font-medium text-gray-900 sm:col-span-2">{{ auth()->user()->name }}</dd></div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3"><dt class="text-sm font-semibold text-gray-500">Email</dt><dd class="text-sm font-medium text-gray-900 sm:col-span-2">{{ auth()->user()->email }}</dd></div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3"><dt class="text-sm font-semibold text-gray-500">Nomor HP</dt><dd class="text-sm font-medium text-gray-900 sm:col-span-2">{{ auth()->user()->no_hp ?: '-' }}</dd></div>
                <div class="grid gap-1 px-6 py-4 sm:grid-cols-3"><dt class="text-sm font-semibold text-gray-500">Alamat</dt><dd class="text-sm font-medium text-gray-900 sm:col-span-2">{{ auth()->user()->alamat ?: '-' }}</dd></div>
            </dl>
        </div>
    </div>
@endsection