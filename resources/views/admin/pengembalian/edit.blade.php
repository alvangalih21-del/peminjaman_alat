@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Pengembalian
        </h1>

        <p class="text-gray-500">
            Ubah data pengembalian alat
        </p>
    </div>


    {{-- Pesan Error --}}
    @if($errors->any())

        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <div class="bg-white rounded-xl shadow p-6">

        <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}"
              method="POST">

            @csrf
            @method('PUT')


            {{-- Peminjaman tidak diubah agar stok dan riwayat tetap konsisten. --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2">
                    Peminjam
                </label>

                <input type="text"
                      value="{{ $pengembalian->peminjaman?->user?->name ?? 'Nama peminjam tidak tersedia' }}"
                       class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                       disabled>

            </div>


            <div class="mb-5">

                <label class="block font-semibold mb-2">
                    Petugas
                </label>

                <input type="text"
                       value="{{ $pengembalian->petugas?->name ?? 'Petugas tidak tersedia' }}"
                       class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                       disabled>

            </div>


            {{-- Tanggal Kembali --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2">
                    Tanggal Kembali
                </label>

                <input type="date"
                       name="tgl_kembali"
                       value="{{ old(
                           'tgl_kembali',
                           $pengembalian->tgl_kembali
                               ? $pengembalian->tgl_kembali->format('Y-m-d')
                               : ''
                       ) }}"
                       class="w-full border rounded-lg px-4 py-2"
                       required>

            </div>


            {{-- Kondisi --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2">
                    Kondisi Alat
                </label>

                <select name="kondisi_kembali"
                        class="w-full border rounded-lg px-4 py-2"
                        required>

                    <option value="Baik"
                        {{ $pengembalian->kondisi_kembali == 'Baik' ? 'selected' : '' }}>
                        Baik
                    </option>

                    <option value="Rusak Ringan"
                        {{ $pengembalian->kondisi_kembali == 'Rusak Ringan' ? 'selected' : '' }}>
                        Rusak Ringan
                    </option>

                    <option value="Rusak"
                        {{ $pengembalian->kondisi_kembali == 'Rusak' ? 'selected' : '' }}>
                        Rusak
                    </option>

                    <option value="Hilang"
                        {{ $pengembalian->kondisi_kembali == 'Hilang' ? 'selected' : '' }}>
                        Hilang
                    </option>

                </select>

            </div>


            {{-- Denda --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2">
                    Denda
                </label>

                <input type="number"
                       name="denda"
                       value="{{ old('denda', $pengembalian->denda) }}"
                       min="0"
                       class="w-full border rounded-lg px-4 py-2"
                       placeholder="Masukkan denda">

            </div>


            {{-- Tombol --}}
            <div class="flex gap-3">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                    Update
                </button>

                <a href="{{ route('admin.pengembalian.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

@endsection