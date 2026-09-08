@extends('layouts.peminjam')

@section('title', 'Dashboard Peminjam')
@section('header-title', 'Dashboard Peminjam')

@section('content')
    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-5 text-blue-900">
        <h1 class="text-xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-sm">Ajukan alat yang dibutuhkan dan pantau status peminjaman Anda.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pengajuan</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $jumlahPengajuan }}</p>
        </div>
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
            <p class="text-sm text-yellow-700">Menunggu Persetujuan</p>
            <p class="mt-2 text-3xl font-bold text-yellow-900">{{ $jumlahDiajukan }}</p>
        </div>
        <div class="rounded-lg border border-green-200 bg-green-50 p-5 shadow-sm">
            <p class="text-sm text-green-700">Sedang Dipinjam</p>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ $jumlahDipinjam }}</p>
        </div>
        <div class="rounded-lg border border-indigo-200 bg-indigo-50 p-5 shadow-sm">
            <p class="text-sm text-indigo-700">Selesai</p>
            <p class="mt-2 text-3xl font-bold text-indigo-900">{{ $jumlahSelesai }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('peminjam.katalog') }}" class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Ajukan Peminjaman
        </a>
        <a href="{{ route('peminjam.riwayat') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Lihat Riwayat
        </a>
    </div>
@endsection