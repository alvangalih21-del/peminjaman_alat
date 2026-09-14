@extends('layouts.peminjam')

@section('title', 'Katalog Alat')

@section('content')
    @if(session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">{{ session('error') }}</div>
    @endif

    <section class="mb-7 overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-900 to-emerald-700 px-6 py-8 text-white shadow-xl lg:px-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.22em] text-emerald-200">Katalog alat</p>
                <h1 class="max-w-xl text-3xl font-black tracking-tight md:text-4xl">Temukan alat untuk kebutuhan Anda.</h1>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-emerald-50 backdrop-blur-sm">
                <span class="font-bold">{{ $alats->total() }}</span> alat tersedia
            </div>
        </div>
        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-200">Pilih alat, tentukan jumlah, lalu kirim pengajuan peminjaman dengan mudah.</p>
    </section>

    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('peminjam.katalog') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ !$kategori ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">Semua</a>
        @foreach($kategoris as $itemKategori)
            <a href="{{ route('peminjam.katalog', ['kategori' => $itemKategori->id]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ (string) $kategori === (string) $itemKategori->id ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">{{ $itemKategori->nama_kategori }}</a>
        @endforeach
    </div>

    <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($alats as $alat)
                <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex h-44 items-center justify-center bg-gradient-to-br from-slate-100 to-emerald-100">
                        @if($alat->gambar)
                            <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-5xl font-black text-slate-300">{{ strtoupper(substr($alat->nama_alat, 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">{{ $alat->kategori->nama_kategori ?? 'Umum' }}</p>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Stok {{ $alat->stok }}</span>
                        </div>

                        <h2 class="mt-3 text-lg font-bold text-slate-900">{{ $alat->nama_alat }}</h2>
                        <p class="mt-2 min-h-10 text-sm leading-6 text-slate-500">{{ $alat->deskripsi ?: 'Alat siap digunakan untuk kebutuhan Anda.' }}</p>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-slate-600">Siap dipinjam</span>
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <input type="checkbox" name="alat_id[]" value="{{ $alat->id }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                Pilih
                            </label>
                        </div>

                        <input type="number" name="jumlah[{{ $alat->id }}]" value="1" min="1" max="{{ $alat->stok }}" class="mt-3 w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-100">
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white py-16 text-center text-slate-500 shadow-sm">Alat yang dicari belum tersedia.</div>
            @endforelse
        </div>

        @if($alats->isNotEmpty())
            <div class="sticky bottom-4 mt-7 flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white/95 p-4 shadow-xl backdrop-blur sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-bold text-slate-900">Siap meminjam alat?</p>
                    <p class="text-sm text-slate-500">Pilih minimal satu alat untuk membuat pengajuan.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="text-sm font-semibold text-slate-700">
                        Kembali pada
                        <input type="date" name="tgl_kembali_plan" required min="{{ now()->addDay()->format('Y-m-d') }}" class="ml-2 rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </label>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Ajukan Peminjaman</button>
                </div>
            </div>
        @endif
    </form>
@endsection