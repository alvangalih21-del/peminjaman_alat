@extends('layouts.peminjam')

@section('title', 'Katalog Alat')

@section('content')
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <section class="mb-7 rounded-xl bg-gray-900 px-6 py-8 text-white shadow-sm lg:px-10">
        <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-emerald-300">Katalog alat</p>
        <h1 class="max-w-xl text-3xl font-black tracking-tight md:text-4xl">Temukan alat untuk kebutuhan Anda.</h1>
        <p class="mt-3 max-w-lg text-sm leading-6 text-gray-300">Pilih alat, tentukan jumlah, lalu kirim pengajuan peminjaman dengan mudah.</p>
    </section>

    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('peminjam.katalog') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ !$kategori ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50' }}">Semua</a>
        @foreach($kategoris as $itemKategori)
            <a href="{{ route('peminjam.katalog', ['kategori' => $itemKategori->id]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ (string) $kategori === (string) $itemKategori->id ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50' }}">{{ $itemKategori->nama_kategori }}</a>
        @endforeach
    </div>

    <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($alats as $alat)
                <article class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex h-40 items-center justify-center bg-gray-100">
                        @if($alat->gambar)
                            <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-5xl font-black text-gray-300">{{ strtoupper(substr($alat->nama_alat, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-bold uppercase tracking-wide text-emerald-600">{{ $alat->kategori->nama_kategori ?? 'Umum' }}</p>
                        <h2 class="mt-1 text-lg font-bold text-gray-900">{{ $alat->nama_alat }}</h2>
                        <p class="mt-2 min-h-10 text-sm text-gray-500">{{ $alat->deskripsi ?: 'Alat siap digunakan untuk kebutuhan Anda.' }}</p>
                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="text-sm font-semibold text-gray-600">Stok {{ $alat->stok }}</span>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                Pilih
                            </label>
                        </div>
                        <input type="number" name="jumlah[{{ $alat->id }}]" value="1" min="1" max="{{ $alat->stok }}" class="mt-3 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white py-16 text-center text-gray-500">Alat yang dicari belum tersedia.</div>
            @endforelse
        </div>

        @if($alats->isNotEmpty())
            <div class="sticky bottom-4 mt-7 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-xl sm:flex-row sm:items-center sm:justify-between">
                <div><p class="font-bold text-gray-900">Siap meminjam alat?</p><p class="text-sm text-gray-500">Pilih minimal satu alat untuk membuat pengajuan.</p></div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="text-sm font-semibold text-gray-700">Kembali pada
                        <input type="date" name="tgl_kembali_plan" required min="{{ now()->addDay()->format('Y-m-d') }}" class="ml-2 rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </label>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">Ajukan Peminjaman</button>
                </div>
            </div>
        @endif
    </form>
@endsection