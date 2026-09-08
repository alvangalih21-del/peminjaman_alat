@extends('layouts.app')

@section('title', 'Detail User – Panel Admin')
@section('header-title', 'Detail Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detail User</h2>
            <p class="text-sm text-gray-500">Informasi profil dan riwayat peminjaman pengguna</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:col-span-1">
            <div class="flex flex-col items-center text-center">
                @if($user->foto_profile)
                    <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil {{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-sm">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                <span class="mt-2 inline-flex px-3 py-1 rounded-full text-xs font-semibold
                    @if($user->role === 'admin') bg-purple-100 text-purple-800
                    @elseif($user->role === 'petugas') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800 @endif">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="mt-6 space-y-4 text-sm text-gray-700">
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">No. HP</p>
                    <p class="font-medium">{{ $user->no_hp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Alamat</p>
                    <p class="font-medium whitespace-pre-line">{{ $user->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Riwayat Peminjaman</h3>
                <span class="text-sm text-gray-500">{{ $user->peminjaman->count() }} transaksi</span>
            </div>

            @if($user->peminjaman->isEmpty())
                <div class="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                    User ini belum memiliki riwayat peminjaman.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($user->peminjaman as $peminjaman)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal pinjam</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jadwal kembali</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->translatedFormat('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($peminjaman->status === 'disetujui') bg-emerald-100 text-emerald-800
                                        @elseif($peminjaman->status === 'diajukan') bg-yellow-100 text-yellow-800
                                        @elseif($peminjaman->status === 'ditolak') bg-red-100 text-red-800
                                        @elseif($peminjaman->status === 'dipinjam') bg-blue-100 text-blue-800
                                        @elseif($peminjaman->status === 'selesai') bg-gray-200 text-gray-700
                                        @else bg-slate-200 text-slate-700 @endif">
                                        {{ ucfirst($peminjaman->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left border-collapse">
                                    <thead>
                                        <tr class="bg-white text-gray-600">
                                            <th class="py-2 px-3 border-b">Nama Alat</th>
                                            <th class="py-2 px-3 border-b">Jumlah</th>
                                            <th class="py-2 px-3 border-b">Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($peminjaman->detailPinjam as $detail)
                                            <tr>
                                                <td class="py-2 px-3 border-b">{{ $detail->alat?->nama_alat ?? '-' }}</td>
                                                <td class="py-2 px-3 border-b">{{ $detail->jumlah }}</td>
                                                <td class="py-2 px-3 border-b">{{ $detail->alat?->status_kondisi ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-3 px-3 text-center text-gray-500">Tidak ada alat dalam peminjaman ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
